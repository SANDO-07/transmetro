<#
 Ejecuta paso a paso las pruebas y verificaciones de calidad del proyecto Transmetro y,
 despues de cada paso, guarda (1) el log en docs/evidencias/logs y (2) una captura de pantalla
 en docs/evidencias/capturas.

 Uso (desde la raiz del proyecto, en una ventana de PowerShell visible y maximizada):
     powershell -ExecutionPolicy Bypass -File scripts\capturar-evidencias.ps1

 Requisitos: PHP 8.4, Composer y (para cobertura) la extension pcov o xdebug.
#>
$ErrorActionPreference = 'Continue'
Add-Type -AssemblyName System.Windows.Forms, System.Drawing

$raiz = Split-Path -Parent $PSScriptRoot
Set-Location $raiz
$capturas = Join-Path $raiz 'docs\evidencias\capturas'
$logs = Join-Path $raiz 'docs\evidencias\logs'
New-Item -ItemType Directory -Force -Path $capturas, $logs | Out-Null

function Tomar-Captura([string]$nombre) {
    Start-Sleep -Milliseconds 800
    $pantalla = [System.Windows.Forms.Screen]::PrimaryScreen.Bounds
    $bmp = New-Object System.Drawing.Bitmap $pantalla.Width, $pantalla.Height
    $g = [System.Drawing.Graphics]::FromImage($bmp)
    $g.CopyFromScreen($pantalla.Location, [System.Drawing.Point]::Empty, $pantalla.Size)
    $bmp.Save((Join-Path $capturas "$nombre.png"), [System.Drawing.Imaging.ImageFormat]::Png)
    $g.Dispose(); $bmp.Dispose()
}

function Paso([string]$id, [string]$titulo, [scriptblock]$comando) {
    Clear-Host
    Write-Host "=== PASO ${id}: $titulo ===" -ForegroundColor Cyan
    Write-Host "Fecha: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')   Equipo: $env:COMPUTERNAME" -ForegroundColor DarkGray
    Write-Host ""
    $salida = & $comando 2>&1 | ForEach-Object { $_.ToString() }
    $codigo = $LASTEXITCODE
    $salida | Out-Host
    $salida | Out-File -FilePath (Join-Path $logs "$id.log") -Encoding utf8
    Write-Host ""
    Write-Host "--- fin del paso $id (codigo de salida: $codigo) ---" -ForegroundColor Cyan
    Tomar-Captura "$id"
}

Paso '01-versiones'          'Versiones de herramientas'                         { php -v; composer --version; git --version; php -m | Select-String -Pattern 'pcov|xdebug|sqlite|mbstring' }
Paso '02-instalacion'        'Instalacion de dependencias (composer install)'    { composer install --no-interaction --no-progress; if (-not (Test-Path .env)) { Copy-Item .env.example .env; php artisan key:generate } }
Paso '03-estructura-pruebas' 'Estructura de las pruebas'                         { Get-ChildItem -Recurse tests -Filter *.php | ForEach-Object { $_.FullName.Replace($raiz + '\', '') } }
Paso '04-unitarias'          'Pruebas UNITARIAS'                                 { vendor\bin\phpunit --testsuite Unit --testdox }
Paso '05-integracion'        'Pruebas de INTEGRACION (Feature)'                  { vendor\bin\phpunit --testsuite Feature --testdox }
Paso '06-regresion'          'Pruebas de REGRESION'                              { vendor\bin\phpunit --filter RegresionTest --testdox }
Paso '07-suite-completa'     'Suite completa'                                    { vendor\bin\phpunit }
Paso '08-cobertura'          'Cobertura de codigo'                               { vendor\bin\phpunit --coverage-clover=coverage.xml --coverage-text }
Paso '09-quality-gate-cobertura' 'Quality gate: cobertura minima 60%'            { php scripts\check-coverage.php coverage.xml 60 }
Paso '10-linter'             'Analisis de estilo (Laravel Pint)'                 { vendor\bin\pint --test }
Paso '11-analisis-estatico'  'Analisis estatico (Larastan / PHPStan)'            { if (-not (Test-Path vendor\bin\phpstan)) { composer require --dev larastan/larastan --no-interaction --no-progress }; New-Item -ItemType Directory -Force storage\framework\phpstan | Out-Null; vendor\bin\phpstan analyse --memory-limit=512M --no-progress }

Clear-Host
Write-Host "Listo. Capturas en: $capturas" -ForegroundColor Green
Write-Host "Logs en:      $logs" -ForegroundColor Green
Write-Host "Pendientes manuales: captura de GitHub Actions y del dashboard de SonarCloud." -ForegroundColor Yellow
