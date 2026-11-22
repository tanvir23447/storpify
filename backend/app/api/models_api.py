import os
from pathlib import Path
from fastapi import APIRouter
from typing import List, Dict, Any

router = APIRouter(prefix="/models", tags=["Model Library"])

CHECKPOINTS_DIR = Path("models/checkpoints")

MODELS_CATALOG = [
    {
        "id": "inswapper_128_fp16",
        "name": "InSwapper 128 FP16",
        "engine": "FaceFusion",
        "category": "Face Swap",
        "version": "1.0.0",
        "license": "InsightFace Research / Non-Commercial",
        "filename": "inswapper_128_fp16.onnx",
        "file_size_mb": 264.8,
        "vram_requirement_gb": 1.5,
        "cpu_support": True,
        "commercial_permitted": False,
        "status": "INSTALLED"
    },
    {
        "id": "arcface_w600k_r50",
        "name": "ArcFace ResNet50 (w600k)",
        "engine": "FaceFusion",
        "category": "Recognition",
        "version": "1.0.0",
        "license": "MIT (Academic/Research)",
        "filename": "arcface_w600k_r50.onnx",
        "file_size_mb": 166.3,
        "vram_requirement_gb": 1.0,
        "cpu_support": True,
        "commercial_permitted": False,
        "status": "INSTALLED"
    },
    {
        "id": "yoloface_8n",
        "name": "YOLOv8 Face Nano",
        "engine": "FaceFusion",
        "category": "Face Detection",
        "version": "8n",
        "license": "AGPL 3.0",
        "filename": "yoloface_8n.onnx",
        "file_size_mb": 12.1,
        "vram_requirement_gb": 0.5,
        "cpu_support": True,
        "commercial_permitted": True,
        "status": "INSTALLED"
    },
    {
        "id": "scrfd_2.5g",
        "name": "SCRFD 2.5G Face Detector",
        "engine": "FaceFusion",
        "category": "Face Detection",
        "version": "2.5g",
        "license": "Apache 2.0",
        "filename": "scrfd_2.5g.onnx",
        "file_size_mb": 3.1,
        "vram_requirement_gb": 0.2,
        "cpu_support": True,
        "commercial_permitted": True,
        "status": "INSTALLED"
    },
    {
        "id": "realesr-general-x4v3",
        "name": "Real-ESRGAN General x4 v3",
        "engine": "AI Enhancement",
        "category": "Image Upscale",
        "version": "v3",
        "license": "BSD 3-Clause",
        "filename": "realesr-general-x4v3.onnx",
        "file_size_mb": 4.8,
        "vram_requirement_gb": 0.5,
        "cpu_support": True,
        "commercial_permitted": True,
        "status": "INSTALLED"
    },
    {
        "id": "edsr_onnxsim_2x",
        "name": "EDSR Super-Resolution 2x",
        "engine": "AI Enhancement",
        "category": "Image Upscale",
        "version": "2x",
        "license": "Apache 2.0",
        "filename": "edsr_onnxsim_2x.onnx",
        "file_size_mb": 5.5,
        "vram_requirement_gb": 0.5,
        "cpu_support": True,
        "commercial_permitted": True,
        "status": "INSTALLED"
    },
    {
        "id": "gfpgan_1.4",
        "name": "GFPGAN Face Restorer v1.4",
        "engine": "AI Enhancement",
        "category": "Face Restore",
        "version": "1.4",
        "license": "Apache 2.0",
        "filename": "gfpgan_1.4.onnx",
        "file_size_mb": 324.5,
        "vram_requirement_gb": 2.0,
        "cpu_support": True,
        "commercial_permitted": True,
        "status": "READY TO INSTALL"
    }
]

@router.get("")
def list_models():
    # Update actual status based on file existence
    result = []
    for m in MODELS_CATALOG:
        path = CHECKPOINTS_DIR / m["filename"]
        item = m.copy()
        item["is_installed"] = path.exists()
        if path.exists():
            item["status"] = "INSTALLED / READY"
        result.append(item)
    return result
