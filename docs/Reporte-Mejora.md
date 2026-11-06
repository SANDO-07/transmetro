# Reporte de Mejora (antes vs. después)

Comparativa de métricas tras la intervención. Completar la columna **Después** con los resultados reales del pipeline y de SonarCloud.

| Métrica | Antes | Después | Mejora |
|---|---|---|---|
| Pruebas totales | 8 | 58 (ver `Matriz-Pruebas.md`) | +50 |
| Pruebas unitarias propias | 0 | 15 | +15 |
| Pruebas de regresión dedicadas | 0 | 6 | +6 |
| Cobertura de líneas | `⟵ línea base` | `⟵ medir` (umbral ≥ 60 %) | |
| Violaciones de estilo (Pint) | 2 archivos | 0 (gate) | -2 |
| Errores PHPStan nivel 5 | `⟵ línea base` | 0 (gate) | |
| Bugs / vulnerabilidades (Sonar) | `⟵ línea base` | `⟵ medir` | |
| Code smells (Sonar) | `⟵ línea base` | `⟵ medir` | |
| Duplicación (Sonar) | `⟵ línea base` | `⟵ medir` | |
| Pipeline CI/CD | No | Sí (4 etapas) | |
| Rama `main` protegida | No | Sí | |

## Gráfico sugerido

Incluir un gráfico de barras antes/después de cobertura y code smells (por ejemplo, exportado del dashboard de SonarCloud) en `docs/evidencias/capturas/`.

## Issues corregidos

Registrar aquí cada issue crítico/mayor encontrado por Larastan, Pint o SonarCloud, con su corrección (commit o PR).

| Issue | Severidad | Herramienta | Corrección |
|---|---|---|---|
| Imports desordenados en `User.php` | Menor | Pint | Reordenados |
| `bootstrap/app.php`: nombre de clase completo y imports desordenados | Menor | Pint | Import de `Rol` agregado y ordenado |
| _(completar con Larastan / SonarCloud)_ | | | |
