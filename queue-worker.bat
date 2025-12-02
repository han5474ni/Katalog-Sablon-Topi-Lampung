@echo off
cd /d C:\laragon\www\Katalog-Sablon-Topi-Lampung
C:\laragon\bin\php\php-8.3.16-nts-Win32-vs16-x64\php.exe artisan queue:work --sleep=3 --tries=3 --max-time=3600
