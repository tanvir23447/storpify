# SIS AI SWAP STUDIO — ENGINE REGISTRY

| Engine ID | Name | Primary Adapter | Status | Hardware Acceleration | CPU Fallback | Health Check Route |
|---|---|---|---|---|---|---|
| `facefusion` | FaceFusion Engine | `ai_engines.facefusion.adapter.FaceFusionAdapter` | READY | ONNX / DirectML / CUDA | Supported | `/api/v1/engines/facefusion/health` |
| `enhancement` | AI Enhancement | `ai_engines.enhancement.adapter.EnhancementAdapter` | READY | Vulkan / OpenCL / CPU | Supported | `/api/v1/engines/enhancement/health` |
| `comfyui` | ComfyUI Workflows | `ai_engines.comfyui.adapter.ComfyUIAdapter` | INTEGRATED | Standalone Host / Local Node | Configurable | `/api/v1/engines/comfyui/health` |
| `liveportrait` | LivePortrait Motion | `ai_engines.liveportrait.adapter.LivePortraitAdapter`| INTEGRATED | ONNX / Torch / DirectML | Supported | `/api/v1/engines/liveportrait/health` |
| `fashn_vton` | FASHN VTON 1.5 | `ai_engines.fashn_vton.adapter.FashnVtonAdapter` | INTEGRATED | Torch / CUDA / CPU | Supported | `/api/v1/engines/fashn_vton/health` |
| `video_studio`| Video Studio Engine| `ai_engines.common.video_processor.VideoStudioEngine`| READY | PyAV / FFmpeg Multi-Thread | Supported | `/api/v1/engines/video/health` |
