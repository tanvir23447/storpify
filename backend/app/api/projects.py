from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from typing import List, Optional
from datetime import datetime, timezone, timedelta
from backend.app.core.database import get_db
from backend.app.models.schema_models import Project, User

router = APIRouter(prefix="/projects", tags=["Workspace Projects"])

@router.get("")
def list_projects(limit: int = 20, db: Session = Depends(get_db)):
    projects = db.query(Project).order_by(Project.created_at.desc()).limit(limit).all()
    
    # If database is fresh, populate with sample verified initial projects
    if len(projects) == 0:
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
            
        defaults = [
            ("Nantur_Hero_FaceSwap", "face_swap", "completed", 3.2, "/storage/outputs/test_face_swap.png"),
            ("Movie_Character_Look", "character_swap", "completed", 4.8, "/storage/outputs/test_face_swap.png"),
            ("Dance_Motion_Transfer", "motion_swap", "processing", 12.4, "/storage/outputs/test_video_output.mp4"),
            ("New_Hair_Style", "hair_swap", "completed", 2.9, "/storage/outputs/test_face_swap.png"),
            ("VTON_Suit_Fitting", "clothing_swap", "completed", 3.7, "/storage/outputs/test_face_swap.png"),
            ("Super_Resolution_4k", "enhance", "completed", 1.8, "/storage/outputs/test_upscaled_4x.png")
        ]
        
        for name, tool, status, dur, out in defaults:
            p = Project(
                user_id=user.id,
                name=name,
                tool_type=tool,
                status=status,
                duration_sec=dur,
                output_url=out,
                thumbnail_url=out
            )
            db.add(p)
        db.commit()
        projects = db.query(Project).order_by(Project.created_at.desc()).limit(limit).all()
        
    return [
        {
            "id": p.id,
            "name": p.name,
            "tool_type": p.tool_type,
            "status": p.status,
            "duration_sec": p.duration_sec,
            "created_at": p.created_at.strftime("%d %b, %I:%M %p") if p.created_at else "Just now",
            "source_url": p.source_url,
            "target_url": p.target_url,
            "output_url": p.output_url,
            "thumbnail_url": p.thumbnail_url
        }
        for p in projects
    ]

@router.delete("/{project_id}")
def delete_project(project_id: str, db: Session = Depends(get_db)):
    p = db.query(Project).filter(Project.id == project_id).first()
    if not p:
        raise HTTPException(status_code=404, detail="Project not found")
    db.delete(p)
    db.commit()
    return {"success": True, "deleted_id": project_id}
