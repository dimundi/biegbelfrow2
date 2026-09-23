@echo off
setlocal
cd /d "%~dp0"
if not exist ".env" copy /y ".env.example" ".env" >nul
if errorlevel 1 exit /b 1
docker compose --env-file ".env" -f "docker-compose.dev.yml" up -d --build --wait --wait-timeout 240
if errorlevel 1 exit /b 1
docker compose --env-file ".env" -f "docker-compose.dev.yml" exec -T wordpress sh /usr/local/bin/init-dev.sh
if errorlevel 1 exit /b 1
echo WordPress dev jest gotowy. Adres: WORDPRESS_URL w deploy/dev/.env
exit /b 0

