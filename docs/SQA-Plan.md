# Plan de Aseguramiento de la Calidad del Software (SQA Plan)

**Proyecto:** Transmetro — sistema de control de líneas, estaciones, buses y pilotos
**Curso:** Aseguramiento de la Calidad del Software (SQA)
**Versión:** 1.0 · **Referencias:** IEEE 730 (Software Quality Assurance Processes), ISO/IEC 25010 (modelo de calidad del producto), IEEE 829 (documentación de pruebas)

## 1. Propósito

Definir las actividades, estándares, métricas y herramientas con las que se asegura que cada cambio al código de Transmetro cumpla umbrales objetivos de calidad antes de integrarse a la rama principal (`main`).

## 2. Alcance

Aplica a todo el código de la aplicación (`app/`, `routes/`, `database/`, `tests/`) y a su pipeline CI/CD. Queda fuera el desarrollo de nuevas funcionalidades y las pruebas de carga, estrés o rendimiento.

### Justificación del stack

El enunciado sugiere React + Express + MySQL pero permite variantes justificadas mientras se mantengan los conceptos de SQA. La aplicación existente es **Laravel 13 (PHP 8.4) + Blade + MySQL**; reescribirla contradice el requisito de reutilizar una base existente. Equivalencias:

| Sugerido | Utilizado | Motivo |
|---|---|---|
| Jest / Supertest / RTL | PHPUnit 12 (pruebas unitarias y HTTP de integración) | Herramienta nativa de Laravel |
| Istanbul / nyc | PCOV + reporte Clover | Cobertura nativa de PHPUnit |
| ESLint + Prettier | Laravel Pint | Linter/formateador oficial del ecosistema |
| (análisis estático) | Larastan (PHPStan nivel 5) + SonarCloud | Detección de bugs, code smells y deuda técnica |
| GitHub Actions | GitHub Actions | Igual |

## 3. Estándares

- Estilo de código: preset `laravel` de Pint (PSR-12 ampliado).
- Análisis estático: PHPStan nivel 5 sobre `app/`.
- Flujo de trabajo: ramas por cambio y *pull request* obligatorio hacia `main`.
- Nombres de pruebas descriptivos, una aserción de negocio por prueba.

## 4. Actividades de SQA

| Actividad | Cuándo | Herramienta |
|---|---|---|
| Pruebas unitarias | cada push/PR | PHPUnit (`tests/Unit`) |
| Pruebas de integración | cada push/PR | PHPUnit + HTTP de Laravel (`tests/Feature`), MySQL en CI |
| Pruebas de regresión | cada push/PR | `RegresionTest` y toda la suite |
| Cobertura de código | cada push/PR | PCOV → `coverage.xml` |
| Análisis de estilo | cada push/PR | Pint |
| Análisis estático | cada push/PR | Larastan, SonarCloud |
| Revisión de código | cada PR | Revisión automática (checks) + revisión manual obligatoria |
| Gestión de deuda técnica | cada sprint | Dashboard de SonarCloud (bugs, code smells, duplicación) |

## 5. Quality gates

El pipeline falla, y la rama `main` rechaza el merge, si no se cumple **cualquiera** de:

| # | Gate | Umbral |
|---|---|---|
| G1 | Todas las pruebas pasan | 100 % aprobadas |
| G2 | Cobertura de líneas | ≥ 60 % (`scripts/check-coverage.php`) |
| G3 | Estilo de código (Pint) | 0 archivos con diferencias |
| G4 | Análisis estático (PHPStan nivel 5) | 0 errores |
| G5 | Quality gate de SonarCloud | Aprobado (sin bugs/vulnerabilidades nuevas, cobertura en código nuevo, duplicación) |

## 6. Métricas

Se miden antes y después de la intervención (ver `Linea-Base-Calidad.md` y `Reporte-Mejora.md`): número de pruebas, % de aprobación, cobertura de líneas, errores de PHPStan, archivos con violaciones de estilo, y en SonarCloud: bugs, vulnerabilidades, code smells, duplicación (%) y deuda técnica.

## 7. Protección de rama

`main` exige: pull request, checks `Build`, `Test`, `Test MySQL`, `Analyze` y `Report` en verde, rama actualizada y una aprobación. Configuración en `Manual-Tecnico.md`, sección 5.

## 8. Roles

| Rol | Responsabilidad |
|---|---|
| Estudiante/desarrollador | Escribir pruebas, corregir issues, mantener el pipeline |
| Docente (revisor) | Evaluar entregables y repositorio |

## 9. Riesgos

| Riesgo | Prob. | Impacto | Mitigación |
|---|---|---|---|
| Reportes SQL (MySQL) no se prueban en SQLite | Alta | Medio | Job `test-mysql` con servicio MySQL 8 |
| Umbral de cobertura demasiado bajo/alto | Media | Medio | Revisar el umbral tras cada ciclo |
| Falsos positivos del análisis estático | Media | Bajo | Ajustar nivel de PHPStan documentando excepciones |
| Token de SonarCloud ausente (forks) | Media | Bajo | Paso condicionado a `SONAR_TOKEN` |
| Dependencias desactualizadas con vulnerabilidades | Media | Alto | `composer audit` y Dependabot |
