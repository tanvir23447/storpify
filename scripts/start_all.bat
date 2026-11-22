@echo off
title SIS AI Swap Studio - Master Launcher
cd /d "%~dp0\.."
echo ========================================================
echo       SIS AI SWAP STUDIO - MASTER INITIALIZER
echo       Owner: Shariar Islam Sohel
echo       Brand: SIS (Ultimate Edition)
echo ========================================================
echo.
echo [1/2] Starting Backend Server (FastAPI on http://127.0.0.1:8000)...
start "SIS Backend API" cmd /k "scripts\start_backend.bat"

timeout /t 3 /nobreak >nul

echo [2/2] Starting Frontend Studio (Next.js on http://localhost:3000)...
start "SIS Frontend Studio" cmd /k "scripts\start_frontend.bat"

echo.
echo ========================================================
echo  All services launched successfully!
echo  Open your browser at: http://localhost:3000
echo  API Documentation at: http://127.0.0.1:8000/docs
echo ========================================================
pause
