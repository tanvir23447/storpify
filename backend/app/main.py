import os
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi.staticfiles import StaticFiles

from backend.app.core.config import settings
from backend.app.core.database import engine, Base
from backend.app.models.schema_models import User
from backend.app.core.security import hash_password

# Initialize tables
Base.metadata.create_all(bind=engine)

# Seed default Super Admin if not present
from backend.app.core.database import SessionLocal
with SessionLocal() as db:
    admin = db.query(User).filter(User.role == "super_admin").first()
    if not admin:
        admin = User(
            email="admin@sis.studio",
            full_name="Sohel Bhai",
            password_hash=hash_password("admin123456"),
            role="super_admin"
        )
        db.add(admin)
        db.commit()

app = FastAPI(
    title=settings.PROJECT_NAME,
    description="Professional Open-Source AI Face, Character, Motion, Head, Hair & Clothing Transformation Platform",
    version=settings.VERSION,
    docs_url="/docs",
    redoc_url="/redoc"
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.CORS_ORIGINS,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Mount storage directory for outputs and uploads
app.mount("/storage", StaticFiles(directory=str(settings.STORAGE_DIR)), name="storage")

# Include Routers
from backend.app.api.auth import router as auth_router
from backend.app.api.system import router as system_router
from backend.app.api.engines import router as engines_router
from backend.app.api.models_api import router as models_router
from backend.app.api.jobs import router as jobs_router
from backend.app.api.projects import router as projects_router
from backend.app.api.upload import router as upload_router

app.include_router(auth_router, prefix=settings.API_V1_PREFIX)
app.include_router(system_router, prefix=settings.API_V1_PREFIX)
app.include_router(engines_router, prefix=settings.API_V1_PREFIX)
app.include_router(models_router, prefix=settings.API_V1_PREFIX)
app.include_router(jobs_router, prefix=settings.API_V1_PREFIX)
app.include_router(projects_router, prefix=settings.API_V1_PREFIX)
app.include_router(upload_router, prefix=settings.API_V1_PREFIX)

@app.get("/")
def root():
    return {
        "brand": "SIS",
        "product": settings.PROJECT_NAME,
        "owner": settings.PROJECT_OWNER,
        "version": settings.VERSION,
        "status": "OPERATIONAL",
        "api_docs": "/docs"
    }

@app.get(f"{settings.API_V1_PREFIX}/health")
def api_health():
    return {
        "status": "HEALTHY",
        "timestamp": "2026-11-21T15:00:00Z",
        "engine_count": 6
    }
