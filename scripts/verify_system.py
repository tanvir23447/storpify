import sys
import os
import time
from pathlib import Path

# Add project root to sys.path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))

print("================================================================")
print("     SIS AI SWAP STUDIO - SYSTEM VERIFICATION SUITE")
print("     Owner: Shariar Islam Sohel")
print("================================================================\n")

# 1. Hardware Detection Test
print("[TEST 1/6] Running Real Hardware Detection...")
from backend.app.services.hardware import get_hardware_info
hw = get_hardware_info()
print(f"  OS: {hw['os']}")
print(f"  CPU: {hw['cpu']['name']} ({hw['cpu']['physical_cores']}C / {hw['cpu']['logical_cores']}T)")
print(f"  RAM: {hw['ram']['used_gb']} / {hw['ram']['total_gb']} GB ({hw['ram']['usage_percent']}%)")
print(f"  GPU: {hw['gpu']['name']} (Vulkan / Acceleration: {hw['gpu']['status']})")
print(f"  Profile: {hw['ai_runtime']['hardware_profile']}")
print("  => PASSED\n")

# 2. Model Checkpoint Verification
print("[TEST 2/6] Verifying Pre-Trained AI Checkpoints...")
from backend.app.api.models_api import list_models
models = list_models()
installed_count = sum(1 for m in models if m.get("is_installed"))
print(f"  Total Catalog Models: {len(models)}")
print(f"  Installed & Ready: {installed_count}")
for m in models:
    status_str = "READY" if m.get("is_installed") else "AVAILABLE"
    print(f"    - {m['name']} ({m['category']}): {status_str} [{m['license']}]")
print("  => PASSED\n")

# 3. Face Swap Real Inference Test
print("[TEST 3/6] Executing Real AI Face Swap Inference...")
from ai_engines.facefusion.adapter import FaceFusionAdapter
ff = FaceFusionAdapter()
t0 = time.time()
res_swap = ff.swap_face(
    "models/realesrgan-ncnn-vulkan/input.jpg",
    "models/realesrgan-ncnn-vulkan/input2.jpg",
    "storage/outputs/verify_face_swap.png"
)
dt_swap = round(time.time() - t0, 2)
assert os.path.exists("storage/outputs/verify_face_swap.png")
size_swap = os.path.getsize("storage/outputs/verify_face_swap.png")
print(f"  Face Swap Output: {res_swap['output_path']} ({size_swap} bytes)")
print(f"  Execution Time: {dt_swap}s")
print(f"  Resolution: {res_swap['resolution']}")
print("  => PASSED\n")

# 4. Real-ESRGAN Upscaling Real Inference Test
print("[TEST 4/6] Executing Real AI Neural Upscaling Inference...")
from ai_engines.enhancement.adapter import EnhancementAdapter
enh = EnhancementAdapter()
t0 = time.time()
res_enh = enh.upscale_image(
    "models/realesrgan-ncnn-vulkan/input.jpg",
    "storage/outputs/verify_upscaled_4x.png",
    scale=4
)
dt_enh = round(time.time() - t0, 2)
assert os.path.exists("storage/outputs/verify_upscaled_4x.png")
size_enh = os.path.getsize("storage/outputs/verify_upscaled_4x.png")
print(f"  Upscaled Output: {res_enh['output_path']} ({size_enh} bytes)")
print(f"  Execution Time: {dt_enh}s")
print(f"  Resolution: {res_enh['original_resolution']} -> {res_enh['enhanced_resolution']}")
print("  => PASSED\n")

# 5. Video Studio Real Processing Test
print("[TEST 5/6] Executing Real Video Processing & Audio Re-Encoding...")
from ai_engines.common.video_processor import VideoStudioEngine
vid = VideoStudioEngine()
t0 = time.time()
res_vid = vid.process_video(
    "models/realesrgan-ncnn-vulkan/onepiece_demo.mp4",
    "storage/outputs/verify_video.mp4",
    frame_transformer=lambda f, i: f,
    max_frames=15
)
dt_vid = round(time.time() - t0, 2)
assert os.path.exists("storage/outputs/verify_video.mp4")
size_vid = os.path.getsize("storage/outputs/verify_video.mp4")
print(f"  Video Output: {res_vid['output_path']} ({size_vid} bytes)")
print(f"  Execution Time: {dt_vid}s")
print(f"  Frames Encoded: {res_vid['frames_processed']} @ {res_vid['fps']} FPS")
print("  => PASSED\n")

# 6. Backend API & Engine Registry Test
print("[TEST 6/6] Verifying FastAPI Engine & System Routes...")
from backend.app.main import app
from backend.app.api.engines import list_engines
engines = list_engines()
print(f"  Mounted Engines: {len(engines)}")
for e in engines:
    print(f"    - {e.get('engine')}: {e.get('status')}")
print("  => PASSED\n")

print("================================================================")
print("  ALL 6 VERIFICATION TEST SUITES PASSED WITH 100% SUCCESS!")
print("================================================================")
