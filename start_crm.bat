@echo off
cd /d D:\projects\osama\shendy-crm

:restart
echo [%date% %time%] 🚀 Starting Laravel server... >> server_log.txt

php artisan serve --host=192.168.0.90 --port=8001

echo [%date% %time%] ❌ Server crashed! Restarting in 5 seconds... >> server_log.txt
timeout /t 5 /nobreak >nul
goto restart
