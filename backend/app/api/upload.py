import os
import uuid
from pathlib import Path
from fastapi import APIRouter, UploadFile, File, HTTPException
from backend.app.core.config import settings

router = APIRouter(prefix="", tags=["File Upload"])

@router.post("/upload")
async def upload_file(file: UploadFile = File(...)):
    ext = file.filename.split(".")[-1].lower() if "." in file.filename else ""
    allowed = settings.ALLOWED_IMAGE_EXTENSIONS.union(settings.ALLOWED_VIDEO_EXTENSIONS)
    
    if ext not in allowed:
        raise HTTPException(status_code=400, detail=f"Unsupported file format: {ext}")
        
    unique_filename = f"{uuid.uuid4().hex[:12]}_{file.filename}"
    file_path = settings.UPLOAD_DIR / unique_filename
    
    with open(file_path, "wb") as buffer:
        content = await file.read()
        buffer.write(content)
        
    return {
        "success": True,
        "filename": unique_filename,
        "original_name": file.filename,
        "file_path": str(file_path),
        "url": f"/storage/uploads/{unique_filename}",
        "size_bytes": len(content)
    }
