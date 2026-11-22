from fastapi import APIRouter
from ai_engines.facefusion.adapter import FaceFusionAdapter
from ai_engines.enhancement.adapter import EnhancementAdapter
from ai_engines.fashn_vton.adapter import FashnVtonAdapter
from ai_engines.comfyui.adapter import ComfyUIAdapter
from ai_engines.liveportrait.adapter import LivePortraitAdapter
from ai_engines.common.video_processor import VideoStudioEngine

router = APIRouter(prefix="/engines", tags=["AI Engine Registry"])

facefusion = FaceFusionAdapter()
enhancement = EnhancementAdapter()
vton = FashnVtonAdapter()
comfyui = ComfyUIAdapter()
liveportrait = LivePortraitAdapter()
video_engine = VideoStudioEngine()

@router.get("")
def list_engines():
    return [
        facefusion.check_health(),
        enhancement.check_health(),
        vton.check_health(),
        comfyui.check_health(),
        liveportrait.check_health(),
        video_engine.check_health()
    ]

@router.get("/{engine_id}/health")
def get_engine_health(engine_id: str):
    if engine_id == "facefusion":
        return facefusion.check_health()
    elif engine_id == "enhancement":
        return enhancement.check_health()
    elif engine_id == "clothing" or engine_id == "fashn_vton":
        return vton.check_health()
    elif engine_id == "comfyui":
        return comfyui.check_health()
    elif engine_id == "liveportrait" or engine_id == "motion":
        return liveportrait.check_health()
    elif engine_id == "video":
        return video_engine.check_health()
    return {"error": f"Unknown engine: {engine_id}"}
