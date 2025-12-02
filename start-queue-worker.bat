:: Laragon Auto-run script for Queue Worker
:: Place this in Laragon's auto-start folder or run manually

@echo off
title Laravel Queue Worker - LGI Store
color 0A

echo ========================================
echo   Laravel Queue Worker - LGI Store
echo ========================================
echo.

cd /d C:\laragon\www\Katalog-Sablon-Topi-Lampung

:loop
echo [%date% %time%] Starting queue worker...
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
echo [%date% %time%] Queue worker stopped. Restarting in 5 seconds...
timeout /t 5 /nobreak
goto loop
