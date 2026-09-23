@echo off
setlocal
cd /d "%~dp0"
if not exist ".env" copy /y ".env.example" ".env" >nul
if errorlevel 1 exit /b 1
docker compose --env-file ".env" -f "docker-compose.dev.yml" config --quiet
if errorlevel 1 exit /b 1
docker compose --env-file ".env" -f "docker-compose.dev.yml" build --pull
if errorlevel 1 exit /b 1
docker compose --env-file ".env" -f "docker-compose.dev.yml" pull db
exit /b %errorlevel%

