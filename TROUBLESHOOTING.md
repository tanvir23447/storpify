# SIS AI SWAP STUDIO — TROUBLESHOOTING GUIDE

## 1. Out-of-Memory (OOM) Errors on Ultra-High-Resolution Images
- **Symptom:** AI processing terminates unexpectedly on 4K or 8K images.
- **Remedy:** The platform implements tiled inference (`tile_size: 512`) with 32px overlap in `EnhancementAdapter`. Ensure tiled mode is active if your machine has less than 8 GB VRAM.

## 2. Inswapper Model Checkpoint Missing
- **Symptom:** `FaceFusionAdapter` reports status `NOT INSTALLED`.
- **Remedy:** Ensure `inswapper_128_fp16.onnx`, `arcface_w600k_r50.onnx`, and `yoloface_8n.onnx` reside in `models/checkpoints/`. You can trigger a one-click verification from the Model Library page (`/models`).

## 3. Port Already in Use (8000 or 3000)
- **Symptom:** Uvicorn or Next.js fails to bind to port.
- **Remedy:** Check running processes using PowerShell:
  ```powershell
  Get-Process -Id (Get-NetTCPConnection -LocalPort 8000).OwningProcess
  ```
  Terminate conflicting process or specify an alternative port in `scripts/start_backend.bat`.
