# Evidencias

Índice de las evidencias del proyecto. Las capturas locales se generan con `scripts/capturar-evidencias.ps1` (carpetas `capturas/` y `logs/`); las de GitHub y SonarQube Cloud se tomaron del navegador y están en esta misma carpeta.

## Ejecución local (capturas/ y logs/)

| Captura | Qué demuestra |
|---|---|
| 01-versiones | Versiones de PHP, Composer y Git |
| 02-instalacion | Instalación de dependencias |
| 03-estructura-pruebas | Estructura de `tests/` |
| 04-unitarias | Pruebas unitarias |
| 05-integracion | Pruebas de integración |
| 06-regresion | Pruebas de regresión |
| 07-suite-completa | Suite completa: 58 pruebas, 135 aserciones, 2 omitidas (requieren MySQL) |
| 08-cobertura | Cobertura de líneas medida en CI (resumen de calidad: 60.13 %) |
| 09-quality-gate-cobertura | Quality gate de cobertura mínima en CI: 273/454 líneas, umbral 60 %, aprobado |
| 10-linter | Laravel Pint: PASS en 61 archivos |
| 11-analisis-estatico | Larastan / PHPStan nivel 5: `[OK] No errors` |
| mejora-antes-despues | Gráfico comparativo del Reporte de Mejora |

Nota sobre 08 y 09: el equipo Windows de desarrollo no tiene un driver de cobertura (Xdebug o PCOV), por lo que el script local no puede medirla. La cobertura se mide en GitHub Actions con PCOV y esas capturas provienen de esa ejecución. El log `logs/08-cobertura.log` conserva la advertencia local ("No code coverage driver available").

El archivo `logs/phpstan-errores.txt` conserva los 19 errores de PHPStan de la línea base (antes de la corrección).

## GitHub y SonarQube Cloud

| Archivo | Qué demuestra |
|---|---|
| 12-pr-bloqueado-por-test-fallido.jpg | PR #2 con test roto: checks fallidos y merge bloqueado |
| 13-log-test-fallido.jpg | Log de CI con la aserción que falló |
| 14-pr-reporte-mejora-checks-verdes.jpg | PR #4 con los 6 checks aprobados y el merge habilitado |
| 15-actions-historial.jpg | Historial de ejecuciones del pipeline (verdes y el fallo intencional) |
| 16-pipeline-etapas.jpg | Etapas del pipeline: Build, Test, Test MySQL, Analyze, Report |
| 17-resumen-calidad.jpg | Resumen de calidad del job Report, con cobertura 60.13 % |
| 18-quality-gate-cobertura-ci.jpg | Log del quality gate de cobertura (273/454, umbral 60 %) |
| 19-sonar-quality-gate.jpg | SonarQube Cloud: Quality Gate Passed, 0 issues, cobertura 61.3 %, 0.0 % duplicación |
| 20-regla-proteccion-main.jpg | Regla de protección de `main`: PR obligatorio y los 5 checks requeridos |
| 21-readme-badges.jpg | README con badges de CI, quality gate, cobertura, bugs y code smells |
