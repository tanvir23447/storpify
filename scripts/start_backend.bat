@echo off
title SIS AI Swap Studio - Backend Server
cd /d "%~dp0\.."
echo ========================================================
echo    SIS AI SWAP STUDIO - BACKEND SERVER (FastAPI)
echo    Self-Hosted AI Face, Character, Motion Transformation
echo ========================================================
set PYTHON_EXE=C:\Users\UESR\AppData\Local\Programs\Stonic AI\resources\voice-runtime\venv\Scripts\python.exe
if not exist "%PYTHON_EXE%" (
    set PYTHON_EXE=python.exe
)
"%PYTHON_EXE%" -m uvicorn backend.app.main:app --host 0.0.0.0 --port 8000 --reload
pause
