#!/bin/bash
echo "========================================================"
echo "    SIS AI SWAP STUDIO - LINUX MASTER INITIALIZER"
echo "    Owner: Shariar Islam Sohel"
echo "========================================================"

# Activate virtualenv if available
if [ -d "venv" ]; then
    source venv/bin/activate
fi

# Launch backend in background
python3 -m uvicorn backend.app.main:app --host 0.0.0.0 --port 8000 &
BACKEND_PID=$!
echo "Backend running with PID: $BACKEND_PID"

# Launch frontend
cd frontend
npm run dev &
FRONTEND_PID=$!
echo "Frontend running with PID: $FRONTEND_PID"

wait $BACKEND_PID $FRONTEND_PID
