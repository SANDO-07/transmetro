# Línea Base de Calidad (antes de la intervención)

Estado del proyecto **antes** de agregar pruebas, linters y pipeline. Los valores marcados `⟵ medir` deben obtenerse ejecutando los comandos sobre el código original (sección 3) y adjuntando la captura.

## 1. Inventario inicial (verificado en el código original)

| Aspecto | Estado inicial |
|---|---|
| Pipeline CI/CD | No existe |
| Linter / formateador | Pint instalado como dependencia, sin configuración ni ejecución automática |
| Análisis estático | No existe |
| Cobertura configurada | No |
| Pruebas | 6 pruebas de reglas de negocio (`ReglasTransmetroTest`) + 1 prueba de ejemplo (`Feature/ExampleTest`) + 1 de ejemplo unitaria |
| Pruebas unitarias propias | 0 |
| Protección de rama | No |
| Documentación | `README.md` de 2 líneas; `AGENTS.md` |

## 2. Métricas iniciales

| Métrica | Valor inicial |
|---|---|
| Pruebas totales / aprobadas | 8 / 8 (100 %), 22 aserciones. PHPUnit 12.5.38, PHP 8.4.27 |
| Cobertura de líneas | No medida (no había herramienta de cobertura configurada) |
| Archivos con violaciones de estilo (Pint) | 2 de 53: `app/Models/User.php` (ordered_imports) y `bootstrap/app.php` (fully_qualified_strict_types, ordered_imports) |
| Errores PHPStan nivel 5 | 19 (tipos de relaciones Eloquent, `catch` muertos, `abort_unless` con objeto) |
| SonarQube Cloud, primer análisis automático (3.2k líneas, incluye vistas Blade) | Security 12 (C) · Reliability 82 (C) · Maintainability 1 (A) · Duplicación 0.9 % · Cobertura: no medida |

## 3. Cómo medir

```bash
git checkout <commit-original>
composer install
vendor/bin/phpunit --coverage-text          # pruebas y cobertura
vendor/bin/pint --test                      # estilo
composer require --dev larastan/larastan
vendor/bin/phpstan analyse                  # estático (usar phpstan.neon)
```

Para SonarCloud: ejecutar un primer análisis antes de aplicar los cambios y guardar la captura del panel como evidencia de la línea base.
