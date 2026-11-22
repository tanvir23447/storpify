# SIS AI SWAP STUDIO — DEPLOYMENT GUIDE

## Deployment Modes

### Mode A: Private Self-Hosted (Local Machine / Private Server)
- All services (Next.js, FastAPI, SQLite/Postgres, Models, Workers) run locally on the owner's workstation or private LAN server.
- Maximum privacy: no telemetry or media leaves the machine.
- Recommended command:
  ```cmd
  scripts\start_all.bat
  ```

### Mode B: Public SaaS Web Platform
- **Frontend Layer:** Deploy `frontend/` to Vercel, Cloudflare Pages, or AWS ECS with environment variable:
  ```env
  NEXT_PUBLIC_API_URL=https://api.yourdomain.com
  ```
- **Inference Server:** Run `backend/` and `ai_engines/` on a dedicated GPU instance (e.g. RunPod, Vast.ai, AWS EC2 g4dn/g5, or an on-premise workstation).
- **Reverse Proxy:** Configure Nginx with SSL (Let's Encrypt), WebSocket upgrade headers, and client max body size:
  ```nginx
  client_max_body_size 500M;
  location / {
      proxy_pass http://127.0.0.1:3000;
  }
  location /api/ {
      proxy_pass http://127.0.0.1:8000;
      proxy_set_header Upgrade $http_upgrade;
      proxy_set_header Connection "upgrade";
  }
  ```
