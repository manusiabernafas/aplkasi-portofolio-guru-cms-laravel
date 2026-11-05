@echo off
REM Setup Portofolio Guru untuk Windows (XAMPP / Laragon). Jalankan dari folder project.
REM Pastikan Apache & MySQL sudah menyala di XAMPP Control Panel.
cd /d "%~dp0"
where php >nul 2>nul
if errorlevel 1 (
  if exist "C:\xampp\php\php.exe" (set PHP=C:\xampp\php\php.exe) else (echo PHP tidak ditemukan. Jalankan dari "Shell" XAMPP. & pause & exit /b 1)
) else (set PHP=php)
if not exist "vendor\autoload.php" (
  echo Menginstal dependency dengan Composer...
  call composer install --no-dev --optimize-autoloader --no-interaction
  if errorlevel 1 (echo Composer gagal / belum terpasang. Unduh di https://getcomposer.org & pause & exit /b 1)
)
for %%D in (storage\framework\sessions storage\framework\views storage\framework\cache\data storage\app\private\berkas storage\logs bootstrap\cache public\uploads) do if not exist "%%D" mkdir "%%D"
if not exist ".env" copy ".env.example" ".env" >nul
%PHP% artisan key:generate --force
REM migrate pertama membuat database portofolio_guru bila belum ada
%PHP% artisan migrate --force
%PHP% artisan migrate:fresh --seed --force
%PHP% artisan optimize:clear
echo.
echo Selesai. Buka http://localhost/portofolio-guru
echo Panel admin: http://localhost/portofolio-guru/admin   (admin / admin123)
pause
