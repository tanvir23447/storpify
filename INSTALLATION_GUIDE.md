# SIS AI SWAP STUDIO — INSTALLATION GUIDE

## System Requirements
- **OS:** Windows 10/11 64-bit or Ubuntu 20.04/22.04 LTS
- **CPU:** Multi-core x86_64 processor (Intel Core i5 / AMD Ryzen 5 or higher)
- **RAM:** Minimum 8 GB (16 GB Recommended)
- **GPU (Optional):** NVIDIA GPU with CUDA 11.8/12.x OR Intel/AMD GPU with Vulkan 1.3 support
- **Disk Space:** 5 GB free disk space for AI models and libraries

---

## 1. Quick Windows Installation
1. Ensure Node.js 18+ is installed on your machine.
2. Clone or open the project folder in your terminal:
   ```cmd
   cd "D:\antigravity project\s"
   ```
3. Run the master launcher:
   ```cmd
   scripts\start_all.bat
   ```
4. The launcher will automatically:
   - Start the FastAPI backend on port 8000
   - Start the Next.js frontend on port 3000
   - Open your browser to `http://localhost:3000`

---

## 2. Linux Installation
```bash
cd "D:/antigravity project/s"
chmod +x scripts/start_all.sh
./scripts/start_all.sh
```

---

## 3. Docker Deployment
```bash
cd docker
docker-compose up -d --build
```
Access the application at `http://localhost:3000`.
