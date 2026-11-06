# Manual Técnico

## 1. Arquitectura del pipeline

```
 push / pull request a main
            │
            ▼
   ┌────────────────┐   ┌──────────────────────────────┐
   │ 1. BUILD       │──▶│ 2. TEST                       │
   │ composer       │   │  · PHPUnit (SQLite) + PCOV     │
   │ install,       │   │  · Gate cobertura ≥ 60 %       │
   │ app boot       │   │ 2b. TEST-MYSQL                 │
   └────────────────┘   │  · Reportes SQL en MySQL 8     │
                        └──────────────┬───────────────┘
                                       ▼
                        ┌──────────────────────────────┐
                        │ 3. ANALYZE                    │
                        │  · Pint (estilo)              │
                        │  · Larastan nivel 5           │
                        │  · SonarCloud + quality gate  │
                        └──────────────┬───────────────┘
                                       ▼
                        ┌──────────────────────────────┐
                        │ 4. REPORT (resumen + falla    │
                        │    si alguna etapa falló)     │
                        └──────────────────────────────┘
```

Archivo: `.github/workflows/ci.yml`.

## 2. Decisiones técnicas

- **PHPUnit con SQLite en memoria** para velocidad; los reportes (SQL de MySQL) se prueban en un job aparte con servicio MySQL.
- **PCOV** para cobertura por ser mucho más rápido que Xdebug.
- **Gate de cobertura propio** (`scripts/check-coverage.php`) sobre el reporte Clover: independiente de la herramienta y fácil de auditar.
- **Larastan** se instala en el job *Analyze* con `composer require --dev` para no alterar `composer.lock`. Recomendado: ejecutar una vez `composer require --dev larastan/larastan` localmente y versionar el lock; luego se puede quitar esa línea del workflow.
- **SonarCloud** con `sonar.qualitygate.wait=true`: el paso falla si el gate de Sonar no se aprueba. Se omite si no existe el secreto `SONAR_TOKEN`.

## 3. Ejecución local

```bash
composer install
cp .env.example .env && php artisan key:generate
vendor/bin/phpunit --testdox                              # pruebas
vendor/bin/phpunit --coverage-clover=coverage.xml --coverage-text   # requiere pcov o xdebug
php scripts/check-coverage.php coverage.xml 60            # gate de cobertura
vendor/bin/pint --test                                    # estilo (sin --test corrige)
vendor/bin/phpstan analyse                                # estático
```

Captura automática de evidencias en Windows: `powershell -ExecutionPolicy Bypass -File scripts\capturar-evidencias.ps1`.

## 4. Configuración de SonarCloud

1. Entrar a sonarcloud.io con la cuenta de GitHub y elegir **Analyze new project** → seleccionar el repositorio.
2. Editar `sonar-project.properties`: `sonar.organization` y `sonar.projectKey` (se muestran en el proyecto).
3. En SonarCloud: *My Account → Security* → generar un token.
4. En GitHub: *Settings → Secrets and variables → Actions → New repository secret* → `SONAR_TOKEN`.
5. En SonarCloud, desactivar *Automatic Analysis* (el análisis lo hace el pipeline).

## 5. Protección de la rama principal

GitHub → *Settings → Branches → Add branch ruleset* (o *Branch protection rule*) para `main`:

- Require a pull request before merging (1 aprobación).
- Require status checks to pass: `Build`, `Test (unitarias, integración y regresión + cobertura)`, `Test (reportes SQL sobre MySQL)`, `Analyze (linter, análisis estático y SonarCloud)`, `Report (resumen de calidad)`.
- Require branches to be up to date before merging.
- Do not allow bypassing.

## 6. Mantenimiento

- Subir el umbral de cobertura gradualmente (`COVERAGE_MIN` en `ci.yml`).
- Subir el nivel de PHPStan (`phpstan.neon`) de forma incremental.
- Revisar semanalmente la deuda técnica en SonarCloud y `composer audit`.
- Ante un fallo del pipeline: abrir el job fallido → revisar el log del paso rojo; para el gate de cobertura, descargar el artefacto `test-reports`.

## 7. Despliegue

El proyecto ya incluye `railway.json` (despliegue en Railway con `php artisan migrate --force --seed`). El despliegue automático (CD) es opcional y no forma parte del pipeline actual.
