# SIVISIT CareVisit Monitor - Deployment Preparation Script
# Jalankan sebelum upload ke InfinityFree
# PowerShell script

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " SIVISIT - Persiapan Deploy InfinityFree " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Install Composer dependencies (production only)
Write-Host "[1/5] Install Composer dependencies..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader
if ($?) { Write-Host "  OK" -ForegroundColor Green } else { Write-Host "  GAGAL" -ForegroundColor Red; exit 1 }

# 2. Install NPM & build frontend
Write-Host "[2/5] Build frontend assets..." -ForegroundColor Yellow
npm install
if ($?) { Write-Host "  NPM install OK" -ForegroundColor Green } else { Write-Host "  NPM install GAGAL" -ForegroundColor Red; exit 1 }
npm run build
if ($?) { Write-Host "  Vite build OK" -ForegroundColor Green } else { Write-Host "  Vite build GAGAL" -ForegroundColor Red; exit 1 }

# 3. Generate APP_KEY if not set
Write-Host "[3/5] Generate APP_KEY..." -ForegroundColor Yellow
if (-not (Select-String -Path ".env" -Pattern "APP_KEY=base64" -Quiet)) {
    php artisan key:generate
    if ($?) { Write-Host "  OK" -ForegroundColor Green } else { Write-Host "  GAGAL" -ForegroundColor Red; exit 1 }
} else {
    Write-Host "  APP_KEY sudah ada" -ForegroundColor Green
}

# 4. Cache Laravel config & routes for production
Write-Host "[4/5] Cache konfigurasi untuk production..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache
Write-Host "  OK" -ForegroundColor Green

# 5. Buat file .env.production
Write-Host "[5/5] File .env.production sudah siap" -ForegroundColor Yellow
Write-Host "  Edit .env.production dengan data database dari InfinityFree" -ForegroundColor Cyan

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Persiapan selesai! " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Langkah selanjutnya:" -ForegroundColor White
Write-Host "1. Edit file .env dengan data database InfinityFree" -ForegroundColor White
Write-Host "2. Upload seluruh folder ke /htdocs/ via FTP" -ForegroundColor White
Write-Host "3. Set permission storage/ ke 755" -ForegroundColor White
Write-Host "4. Import database via PHPMyAdmin" -ForegroundColor White
Write-Host "5. Buka https://domain-anda.infinityfreeapp.com/app/" -ForegroundColor White
