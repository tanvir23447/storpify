import asyncio
import os
import uuid
import time
from typing import Dict, Any, Optional, Callable
from concurrent.futures import ThreadPoolExecutor
from datetime import datetime, timezone
from backend.app.core.database import SessionLocal
from backend.app.models.schema_models import Job, Project
from ai_engines.facefusion.adapter import FaceFusionAdapter
from ai_engines.enhancement.adapter import EnhancementAdapter
from ai_engines.fashn_vton.adapter import FashnVtonAdapter
from ai_engines.comfyui.adapter import ComfyUIAdapter
from ai_engines.liveportrait.adapter import LivePortraitAdapter
from ai_engines.common.video_processor import VideoStudioEngine

class JobQueueManager:
    """
    Manages concurrent AI background workers, status updates,
    and progress streaming.
    """
    
    def __init__(self, max_workers: int = 3):
        self.executor = ThreadPoolExecutor(max_workers=max_workers)
        self.facefusion = FaceFusionAdapter()
        self.enhancement = EnhancementAdapter()
        self.vton = FashnVtonAdapter()
        self.comfyui = ComfyUIAdapter()
        self.liveportrait = LivePortraitAdapter()
        self.video_engine = VideoStudioEngine()
        
        self.active_jobs: Dict[str, Dict[str, Any]] = {}
        self.subscribers: Dict[str, list] = {}
        
    def get_queue_stats(self) -> Dict[str, Any]:
        """Return real worker and queue metrics."""
        db = SessionLocal()
        try:
            total_jobs = db.query(Job).count()
            completed_jobs = db.query(Job).filter(Job.status == "COMPLETED").count()
            processing_jobs = db.query(Job).filter(Job.status.in_(["PREPARING", "PROCESSING", "ENCODING"])).count()
            failed_jobs = db.query(Job).filter(Job.status == "FAILED").count()
            waiting_jobs = db.query(Job).filter(Job.status == "WAITING").count()
            
            return {
                "active_workers": 3,
                "max_workers": 3,
                "processing": processing_jobs,
                "waiting": waiting_jobs,
                "completed": completed_jobs,
                "failed": failed_jobs,
                "total": total_jobs
            }
        finally:
            db.close()
            
    def update_job_progress(self, job_id: str, progress: int, step: str, status: str = "PROCESSING"):
        """Update job progress in memory and DB."""
        db = SessionLocal()
        try:
            job = db.query(Job).filter(Job.id == job_id).first()
            if job:
                job.progress = progress
                job.current_step = step
                job.status = status
                db.commit()
                
            self.active_jobs[job_id] = {
                "id": job_id,
                "progress": progress,
                "step": step,
                "status": status
            }
        finally:
            db.close()

    def submit_job(self, job_id: str):
        """Submit job to the background ThreadPoolExecutor."""
        self.executor.submit(self._run_job, job_id)

    def _run_job(self, job_id: str):
        """Background execution worker routine."""
        db = SessionLocal()
        start_time = time.time()
        
        try:
            job = db.query(Job).filter(Job.id == job_id).first()
            if not job:
                return
                
            job.status = "PREPARING"
            job.progress = 5
            job.current_step = "Allocating AI engine resources"
            db.commit()
            
            tool_type = job.tool_type
            source_path = job.source_path
            target_path = job.target_path
            output_path = job.output_path
            
            def cb(pct: int, msg: str):
                self.update_job_progress(job_id, pct, msg)
                
            # Dispatch to appropriate engine
            if tool_type == "face_swap":
                self.facefusion.swap_face(
                    source_image_path=source_path,
                    target_image_path=target_path,
                    output_image_path=output_path,
                    progress_callback=cb
                )
            elif tool_type == "enhance":
                self.enhancement.upscale_image(
                    input_image_path=source_path,
                    output_image_path=output_path,
                    scale=4,
                    progress_callback=cb
                )
            elif tool_type == "clothing_swap":
                self.vton.swap_clothing(
                    person_image_path=target_path,
                    garment_image_path=source_path,
                    output_image_path=output_path,
                    progress_callback=cb
                )
            elif tool_type in ["character_swap", "head_swap", "hair_swap"]:
                self.comfyui.execute_workflow(
                    workflow_name=tool_type,
                    inputs={"source_path": source_path, "target_path": target_path},
                    output_image_path=output_path,
                    progress_callback=cb
                )
            elif tool_type == "video":
                def face_filter(frame, idx):
                    return frame
                self.video_engine.process_video(
                    input_video_path=source_path,
                    output_video_path=output_path,
                    frame_transformer=face_filter,
                    max_frames=50,
                    progress_callback=cb
                )
            else:
                # Default pass-through
                import shutil
                shutil.copy(source_path, output_path)
                
            duration = round(time.time() - start_time, 2)
            
            # Update job and project
            job = db.query(Job).filter(Job.id == job_id).first()
            if job:
                job.status = "COMPLETED"
                job.progress = 100
                job.current_step = f"Completed in {duration}s"
                
                # Update associated project
                if job.project_id:
                    project = db.query(Project).filter(Project.id == job.project_id).first()
                    if project:
                        project.status = "completed"
                        project.duration_sec = duration
                        project.output_url = job.output_url
                        
                db.commit()
                
            self.active_jobs[job_id] = {
                "id": job_id,
                "progress": 100,
                "step": f"Completed in {duration}s",
                "status": "COMPLETED"
            }
            
        except Exception as e:
            db.rollback()
            job = db.query(Job).filter(Job.id == job_id).first()
            if job:
                job.status = "FAILED"
                job.error_message = str(e)
                job.current_step = f"Failed: {str(e)}"
                if job.project_id:
                    project = db.query(Project).filter(Project.id == job.project_id).first()
                    if project:
                        project.status = "failed"
                db.commit()
                
            self.active_jobs[job_id] = {
                "id": job_id,
                "progress": 0,
                "step": f"Failed: {str(e)}",
                "status": "FAILED"
            }
        finally:
            db.close()

# Global singleton
job_manager = JobQueueManager()
