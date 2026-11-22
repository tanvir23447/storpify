# SIS AI SWAP STUDIO — SYSTEM ARCHITECTURE

## 1. High-Level Architecture

```
                    +------------------------------------+
                    |  SIS AI Swap Studio Web Frontend   |
                    | (Next.js, Tailwind CSS, Zustand)   |
                    +-----------------+------------------+
                                      | HTTP REST / SSE / WS
                                      v
                    +------------------------------------+
                    |       FastAPI Backend Core         |
                    |   (Auth, Router, Jobs, Storage)    |
                    +--------+------------------+--------+
                             |                  |
               SQLAlchemy DB |                  | Job Dispatcher
                             v                  v
                   +----------------+   +-------------------+
                   | SQLite/Postgres|   | Local Job Queue   |
                   +----------------+   +---------+---------+
                                                  |
                    +-----------------------------+-----------------------------+
                    |                             |                             |
                    v                             v                             v
          +-------------------+         +-------------------+         +-------------------+
          | FaceFusion Engine |         | Enhancement Engine|         | ComfyUI Engine    |
          | (SCRFD, ArcFace,  |         | (Real-ESRGAN NCNN |         | (Workflows, VTON, |
          |  Inswapper 128)   |         |  Vulkan & GFPGAN) |         |  Portrait Motion) |
          +-------------------+         +-------------------+         +-------------------+
                    |                             |                             |
                    +-----------------------------+-----------------------------+
                                                  |
                                                  v
                                      +-----------------------+
                                      | Model & License Store |
                                      | (Verified Checkpoints)|
                                      +-----------------------+
```

## 2. Directory Structure

- `frontend/`: Next.js 15 app, components, hooks, state stores, locales (en, bn).
- `backend/app/`: FastAPI application, routers, services, models, schemas.
- `ai_engines/`: Independent Python adapters wrapping model inference.
- `models/`: Model weights directory with SHA256 manifests and license definitions.
- `storage/`: User uploads, generated outputs, temporary processing frames.
- `docs/`: Comprehensive technical documentation.
- `scripts/`: Platform initialization, test, and startup scripts.
