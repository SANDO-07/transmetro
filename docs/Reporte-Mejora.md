# Reporte de Mejora (antes vs. después)

Comparativa de métricas tras la intervención. Completar la columna **Después** con los resultados reales del pipeline y de SonarCloud.

| Métrica | Antes | Después | Mejora |
|---|---|---|---|
| Pruebas totales | 8 | 58 (ver `Matriz-Pruebas.md`) | +50 |
| Pruebas unitarias propias | 0 | 15 | +15 |
| Pruebas de regresión dedicadas | 0 | 6 | +6 |
| Cobertura de líneas (SonarQube Cloud, 569 líneas por cubrir) | No medida | 61.3 % (umbral del plan: ≥ 60 %). PHPUnit/Clover: 66.26 % de líneas | Cumple el umbral |
| Violaciones de estilo (Pint) | 2 archivos | 0 (gate) | -2 |
| Errores PHPStan nivel 5 | 19 | 0 (gate) | -19 |
| Vulnerabilidades / bugs (Sonar, código PHP) | Ver nota | 0 / 0 (calificación A) | Ver nota |
| Code smells / Maintainability (Sonar) | 1 (A) | 0 (A) | -1 |
| Duplicación (Sonar) | 0.9 % | 0.0 % | -0.9 pp |
| Pipeline CI/CD | No | Sí (4 etapas) | |
| Rama `main` protegida | No | Sí | |

## Nota sobre las métricas de SonarQube Cloud

El primer análisis (automático, 3.2k líneas) incluía las vistas Blade y reportó Security 12 y Reliability 82. El análisis posterior, ejecutado desde el pipeline, excluye `resources/views/**` (plantillas HTML mezcladas con PHP) por configuración en `sonar-project.properties` y analiza 1.5k líneas de PHP, con 0 issues. **La diferencia se debe a la exclusión del análisis y no a correcciones del código**; los issues de las vistas siguen sin revisar. Las correcciones reales de esta intervención son las de Pint (2 archivos) y Larastan (19 errores).

## Nota sobre la cobertura

Un primer análisis en SonarQube dio 45.0 % sobre 775 líneas porque contaba `routes`, migraciones y seeders, que PHPUnit no medía. Se alineó el alcance (`phpunit.xml` incluye `app` y `routes`; `sonar-project.properties` excluye `database/**` de la cobertura) y el análisis posterior dio 61.3 % sobre 569 líneas. El cambio es de alcance de medición, no de pruebas nuevas.

## Gráfico sugerido

Incluir un gráfico de barras antes/después de cobertura y code smells (por ejemplo, exportado del dashboard de SonarCloud) en `docs/evidencias/capturas/`.

## Issues corregidos

Registrar aquí cada issue crítico/mayor encontrado por Larastan, Pint o SonarCloud, con su corrección (commit o PR).

| Issue | Severidad | Herramienta | Corrección |
|---|---|---|---|
| Imports desordenados en `User.php` | Menor | Pint | Reordenados |
| `bootstrap/app.php`: nombre de clase completo y imports desordenados | Menor | Pint | Import de `Rol` agregado y ordenado |
| _(completar con Larastan / SonarCloud)_ | | | |
