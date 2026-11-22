import uuid
from datetime import datetime, timezone
from sqlalchemy import Column, String, Integer, Float, Boolean, DateTime, Text, ForeignKey
from sqlalchemy.orm import relationship
from backend.app.core.database import Base

def generate_uuid() -> str:
    return str(uuid.uuid4())

class User(Base):
    __tablename__ = "users"
    
    id = Column(String(36), primary_key=True, default=generate_uuid)
    email = Column(String(255), unique=True, index=True, nullable=False)
    full_name = Column(String(255), nullable=False)
    password_hash = Column(String(255), nullable=False)
    role = Column(String(50), default="user")  # super_admin, admin, user
    is_active = Column(Boolean, default=True)
    created_at = Column(DateTime, default=lambda: datetime.now(timezone.utc))
    
    projects = relationship("Project", back_populates="user", cascade="all, delete-orphan")
    jobs = relationship("Job", back_populates="user", cascade="all, delete-orphan")

class Project(Base):
    __tablename__ = "projects"
    
    id = Column(String(36), primary_key=True, default=generate_uuid)
    user_id = Column(String(36), ForeignKey("users.id"), nullable=False)
    name = Column(String(255), nullable=False)
    tool_type = Column(String(50), nullable=False)  # face_swap, character_swap, motion_swap, head_swap, hair_swap, clothing_swap, enhance, video
    status = Column(String(50), default="created")  # created, processing, completed, failed
    duration_sec = Column(Float, default=0.0)
    source_url = Column(String(500), nullable=True)
    target_url = Column(String(500), nullable=True)
    output_url = Column(String(500), nullable=True)
    thumbnail_url = Column(String(500), nullable=True)
    settings_json = Column(Text, default="{}")
    created_at = Column(DateTime, default=lambda: datetime.now(timezone.utc))
    
    user = relationship("User", back_populates="projects")
    jobs = relationship("Job", back_populates="project", cascade="all, delete-orphan")

class Job(Base):
    __tablename__ = "jobs"
    
    id = Column(String(36), primary_key=True, default=generate_uuid)
    project_id = Column(String(36), ForeignKey("projects.id"), nullable=True)
    user_id = Column(String(36), ForeignKey("users.id"), nullable=False)
    tool_type = Column(String(50), nullable=False)
    engine_id = Column(String(50), nullable=False)
    status = Column(String(50), default="WAITING")  # WAITING, PREPARING, PROCESSING, ENCODING, COMPLETED, FAILED, CANCELLED
    progress = Column(Integer, default=0)
    current_step = Column(String(255), default="Initialized")
    source_path = Column(String(500), nullable=True)
    target_path = Column(String(500), nullable=True)
    output_path = Column(String(500), nullable=True)
    output_url = Column(String(500), nullable=True)
    error_message = Column(Text, nullable=True)
    created_at = Column(DateTime, default=lambda: datetime.now(timezone.utc))
    updated_at = Column(DateTime, default=lambda: datetime.now(timezone.utc), onupdate=lambda: datetime.now(timezone.utc))
    
    project = relationship("Project", back_populates="jobs")
    user = relationship("User", back_populates="jobs")

class AIModelRecord(Base):
    __tablename__ = "ai_models"
    
    id = Column(String(100), primary_key=True)
    name = Column(String(255), nullable=False)
    engine = Column(String(50), nullable=False)
    category = Column(String(50), nullable=False)
    version = Column(String(50), default="1.0.0")
    license_type = Column(String(100), default="Open-Source")
    license_url = Column(String(500), nullable=True)
    checkpoint_url = Column(String(500), nullable=True)
    checksum = Column(String(100), nullable=True)
    file_size_mb = Column(Float, default=0.0)
    vram_req_gb = Column(Float, default=0.0)
    cpu_compatible = Column(Boolean, default=True)
    is_installed = Column(Boolean, default=False)
    status = Column(String(50), default="NOT INSTALLED")  # READY, INSTALLED, NOT INSTALLED, LICENSE REVIEW REQUIRED, ERROR
    local_path = Column(String(500), nullable=True)

class AuditLog(Base):
    __tablename__ = "audit_logs"
    
    id = Column(String(36), primary_key=True, default=generate_uuid)
    user_id = Column(String(36), nullable=True)
    action = Column(String(100), nullable=False)
    details = Column(Text, nullable=True)
    ip_address = Column(String(50), nullable=True)
    timestamp = Column(DateTime, default=lambda: datetime.now(timezone.utc))
