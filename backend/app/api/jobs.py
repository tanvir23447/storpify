import os
import uuid
import asyncio
from fastapi import APIRouter, Depends, HTTPException
from pydantic import BaseModel
from typing import Optional, Dict, Any
from sqlalchemy.orm import Session
from sse_starlette.sse import EventSourceResponse

from backend.app.core.database import get_db
from backend.app.core.config import settings
from backend.app.models.schema_models import Job, Project, User
from backend.app.services.job_queue import job_manager

router = APIRouter(prefix="/jobs", tags=["AI Processing Jobs"])

class JobCreateRequest(BaseModel):
    project_name: Optional[str] = "AI Transformation"
    tool_type: str  # face_swap, enhance, clothing_swap, character_swap, motion_swap, head_swap, hair_swap, video
    source_url: str
    target_url: Optional[str] = None
    settings: Optional[Dict[str, Any]] = {}
    user_id: Optional[str] = None

def _resolve_local_path(url_or_path: str) -> str:
    """Resolve storage URL or path to absolute filesystem path."""
    if not url_or_path:
        return ""
    if "/storage/uploads/" in url_or_path:
        filename = url_or_path.split("/storage/uploads/")[-1]
        return str(settings.UPLOAD_DIR / filename)
    elif "/storage/outputs/" in url_or_path:
        filename = url_or_path.split("/storage/outputs/")[-1]
        return str(settings.OUTPUT_DIR / filename)
    return url_or_path

@router.post("/create")
def create_job(req: JobCreateRequest, db: Session = Depends(get_db)):
    # Find or default user
    user = None
    if req.user_id:
        user = db.query(User).filter(User.id == req.user_id).first()
    if not user:
        user = db.query(User).first()
        if not user:
            user = User(
                email="admin@sis.studio",
                full_name="Sohel Bhai",
                password_hash="system_default",
                role="super_admin"
            )
            db.add(user)
            db.commit()
            db.refresh(user)
            
    source_local = _resolve_local_path(req.source_url)
    target_local = _resolve_local_path(req.target_url) if req.target_url else None
    
    out_ext = "mp4" if req.tool_type in ["video", "motion_swap"] else "png"
    out_filename = f"out_{uuid.uuid4().hex[:10]}.{out_ext}"
    output_local = str(settings.OUTPUT_DIR / out_filename)
    output_url = f"/storage/outputs/{out_filename}"
    
    # Create Project record
    project = Project(
        user_id=user.id,
        name=req.project_name or f"{req.tool_type.replace('_', ' ').title()} Project",
        tool_type=req.tool_type,
        status="processing",
        source_url=req.source_url,
        target_url=req.target_url,
        output_url=output_url,
        thumbnail_url=output_url
    )
    db.add(project)
    db.commit()
    db.refresh(project)
    
    # Create Job record
    job = Job(
        project_id=project.id,
        user_id=user.id,
        tool_type=req.tool_type,
        engine_id=req.tool_type,
        status="WAITING",
        progress=0,
        current_step="Queued for AI worker",
        source_path=source_local,
        target_path=target_local,
        output_path=output_local,
        output_url=output_url
    )
    db.add(job)
    db.commit()
    db.refresh(job)
    
    # Submit to worker pool
    job_manager.submit_job(job.id)
    
    return {
        "success": True,
        "job_id": job.id,
        "project_id": project.id,
        "status": job.status,
        "output_url": output_url
    }

@router.get("/{job_id}")
def get_job_status(job_id: str, db: Session = Depends(get_db)):
    job = db.query(Job).filter(Job.id == job_id).first()
    if not job:
        raise HTTPException(status_code=404, detail="Job not found")
        
    return {
        "id": job.id,
        "project_id": job.project_id,
        "tool_type": job.tool_type,
        "status": job.status,
        "progress": job.progress,
        "current_step": job.current_step,
        "output_url": job.output_url,
        "error_message": job.error_message,
        "created_at": str(job.created_at)
    }

@router.get("/{job_id}/stream")
async def stream_job_progress(job_id: str, db: Session = Depends(get_db)):
    """SSE endpoint for live job status and progress streaming."""
    async def event_generator():
        while True:
            # Query status
            job = db.query(Job).filter(Job.id == job_id).first()
            if not job:
                yield {"data": '{"error": "Job not found"}'}
                break
                
            data = {
                "id": job.id,
                "status": job.status,
                "progress": job.progress,
                "current_step": job.current_step,
                "output_url": job.output_url
            }
            import json
            yield {"data": json.dumps(data)}
            
            if job.status in ["COMPLETED", "FAILED", "CANCELLED"]:
                break
                
            await asyncio.sleep(0.5)
            
    return EventSourceResponse(event_generator())
