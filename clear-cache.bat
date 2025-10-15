@echo off
echo Clearing Laravel cache...
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear
echo.
echo Cache cleared successfully!
echo.
pause