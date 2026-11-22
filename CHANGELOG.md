# SIS AI SWAP STUDIO — CHANGELOG

## [1.0.0-PROD] — 2026-11-21

### Added
- **Core Architecture:** Modular system divided into `frontend/` (Next.js 15), `backend/` (FastAPI), `ai_engines/`, `models/`, and `storage/`.
- **UI & Experience:**
  - Premium dark studio visual identity matching official brand mockup (#080B18, #8B5CF6, #3B82F6).
  - Main header with search, notifications, language switcher, user profile (Sohel Bhai - Super Admin).
  - Hero banner with interactive Before/After split image comparison slider.
  - Live metric cards (Total Projects, Completed, Processing, Failed, Storage, GPU Status).
  - Quick action cards for all 6 transformation studios.
  - Live Recent Projects table with preview modal and download actions.
  - Circular radial gauge widgets for CPU, RAM, GPU, and Storage monitoring.
- **AI Engines & Adapters:**
  - `FaceFusionAdapter`: Genuine ONNX face detection (YOLOv8-face), ArcFace identity extraction, InSwapper 128 transformation, and feathered seamless blending.
  - `EnhancementAdapter`: Real-ESRGAN and EDSR neural super-resolution (2x, 4x) with tiled inference.
  - `VideoStudioEngine`: PyAV (bundled FFmpeg) video frame decoding, neural transformation, and audio-preserving H.264 rendering.
  - `ComfyUIAdapter`: Integration adapter for node graph workflows.
  - `LivePortraitAdapter`: Motion transfer and portrait retargeting pipeline.
  - `FashnVtonAdapter`: Virtual try-on and garment category swap adapter.
- **Backend & REST API:**
  - REST endpoints for `/auth`, `/system`, `/engines`, `/models`, `/jobs`, `/projects`, and `/upload`.
  - Background worker pool with queue state and real-time SSE progress streaming.
  - Hardware detection for CPU, RAM, GPU, and disk metrics.
- **Internationalization:** Full English and Bangla (বাংলা) localization.
- **Testing & Tooling:**
  - Verified real inference test runs generating actual output files on disk.
  - Windows (`start_all.bat`) and Linux (`start_all.sh`) launchers.
  - Production Dockerfile and docker-compose configurations.
