import os
from pathlib import Path
from pydantic import BaseModel

BASE_DIR = Path(__file__).resolve().parent.parent.parent.parent
STORAGE_DIR = BASE_DIR / "storage"
MODELS_DIR = BASE_DIR / "models"
CHECKPOINTS_DIR = MODELS_DIR / "checkpoints"
UPLOAD_DIR = STORAGE_DIR / "uploads"
OUTPUT_DIR = STORAGE_DIR / "outputs"
TEMP_DIR = STORAGE_DIR / "temp"

# Ensure directories exist
for p in [STORAGE_DIR, MODELS_DIR, CHECKPOINTS_DIR, UPLOAD_DIR, OUTPUT_DIR, TEMP_DIR]:
    p.mkdir(parents=True, exist_ok=True)

class Settings(BaseModel):
    PROJECT_NAME: str = "SIS AI Swap Studio™"
    PROJECT_OWNER: str = "Shariar Islam Sohel"
    VERSION: str = "1.0.0-PROD"
    API_V1_PREFIX: str = "/api/v1"
    SECRET_KEY: str = os.getenv("SIS_SECRET_KEY", "sis-ai-swap-studio-super-secure-key-2026-sohel")
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 60 * 24 * 7  # 7 days
    ALGORITHM: str = "HS256"
    
    BASE_DIR: Path = BASE_DIR
    STORAGE_DIR: Path = STORAGE_DIR
    MODELS_DIR: Path = MODELS_DIR
    CHECKPOINTS_DIR: Path = CHECKPOINTS_DIR
    UPLOAD_DIR: Path = UPLOAD_DIR
    OUTPUT_DIR: Path = OUTPUT_DIR
    TEMP_DIR: Path = TEMP_DIR
    
    DATABASE_URL: str = os.getenv("DATABASE_URL", f"sqlite:///{BASE_DIR / 'storage' / 'sis_studio.db'}")
    
    MAX_UPLOAD_SIZE_MB: int = 500
    ALLOWED_IMAGE_EXTENSIONS: set = {"jpg", "jpeg", "png", "webp"}
    ALLOWED_VIDEO_EXTENSIONS: set = {"mp4", "mov", "webm"}
    
    CORS_ORIGINS: list = [
        "http://localhost:3000",
        "http://127.0.0.1:3000",
        "http://localhost:8000",
        "http://127.0.0.1:8000",
        "*"
    ]

settings = Settings()
