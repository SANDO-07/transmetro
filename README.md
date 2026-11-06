# Transmetro

[![CI](https://github.com/SANDO-07/transmetro/actions/workflows/ci.yml/badge.svg)](https://github.com/SANDO-07/transmetro/actions/workflows/ci.yml)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=SANDO-07_transmetro&metric=alert_status)](https://sonarcloud.io/dashboard?id=SANDO-07_transmetro)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=SANDO-07_transmetro&metric=coverage)](https://sonarcloud.io/dashboard?id=SANDO-07_transmetro)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=SANDO-07_transmetro&metric=bugs)](https://sonarcloud.io/dashboard?id=SANDO-07_transmetro)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=SANDO-07_transmetro&metric=code_smells)](https://sonarcloud.io/dashboard?id=SANDO-07_transmetro)

Sistema de control del Transmetro (líneas, estaciones, buses, pilotos, alertas y reportes). Laravel 13 + Blade + MySQL.

Proyecto final de **Aseguramiento de la Calidad del Software (SQA)**: pipeline CI/CD con quality gates integrados.


## Stack

PHP 8.4 · Laravel 13 · MySQL 8 · Blade/Bootstrap · PHPUnit · PCOV · Laravel Pint · Larastan · SonarCloud · GitHub Actions

## Instalación

```bash
composer install
cp .env.example .env && php artisan key:generate
# configurar DB_* en .env (MySQL) y luego:
php artisan migrate --seed
php artisan serve
```

Usuarios de demostración: `admin@transmetro.gt` / `admin123` · `operador@transmetro.gt` / `operador123`.

## Calidad

```bash
vendor/bin/phpunit --testdox       # pruebas
vendor/bin/pint --test             # estilo
vendor/bin/phpstan analyse         # análisis estático
```

El pipeline (`.github/workflows/ci.yml`) ejecuta **build → test → analyze → report** y bloquea el merge si falla alguno de los quality gates (pruebas, cobertura ≥ 60 %, Pint, PHPStan, SonarCloud).

## Documentación (`docs/`)

- [SQA-Plan.md](docs/SQA-Plan.md)
- [Matriz-Pruebas.md](docs/Matriz-Pruebas.md)
- [Linea-Base-Calidad.md](docs/Linea-Base-Calidad.md)
- [Reporte-Mejora.md](docs/Reporte-Mejora.md)
- [Manual-Tecnico.md](docs/Manual-Tecnico.md)
- [Evidencias](docs/evidencias/README.md)
