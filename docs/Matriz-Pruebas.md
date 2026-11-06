# Matriz de Pruebas

Casos de prueba automatizados del proyecto Transmetro (PHPUnit). La columna **Resultado obtenido** se completa con la salida real de `vendor/bin/phpunit --testdox` (ver `docs/evidencias/`) o de la ejecución en GitHub Actions.

Estado de ejecución: **Aprobada**. Todas las pruebas de esta matriz pasan en GitHub Actions: el job `Test` ejecuta 58 pruebas (135 aserciones; 2 omitidas por requerir MySQL) con SQLite en memoria y el job `Test MySQL` ejecuta las 2 pruebas de `ReportesMysqlTest` contra MySQL 8. Evidencia: capturas del pipeline en `docs/evidencias/` y ejecución del PR #1 (commit `d4fd50f`).

### Prueba negativa del quality gate

Para demostrar que el pipeline bloquea código defectuoso, se abrió el PR #2 con un cambio intencional en `ReglasServicioTest::test_estado_buses_ok_cuando_cumple_el_rango` (valor esperado `'fallo-intencional'` en lugar de `'ok'`). Resultado: el job `Test` falló (1 fallo de 58), `Analyze` quedó omitido, `Report` falló, y GitHub bloqueó el botón de merge por los checks requeridos. El PR se cerró sin mergear y la rama se eliminó. Capturas: `12-pr-bloqueado-por-test-fallido.jpg` y `13-log-test-fallido.jpg`.

| ID | Tipo | Prueba | Entrada | Resultado esperado | Resultado obtenido |
|---|---|---|---|---|---|
| TC-01 | Integración | `AutenticacionTest::login_correcto_del_admin_redirige_al_inicio` | credenciales admin válidas | redirige a / y queda autenticado | Aprobada (CI) |
| TC-02 | Integración | `AutenticacionTest::login_con_contrasena_incorrecta_falla` | contraseña errónea | error en email; invitado | Aprobada (CI) |
| TC-03 | Integración | `AutenticacionTest::login_exige_correo_valido` | email "no-es-correo" | error de validación | Aprobada (CI) |
| TC-04 | Integración | `AutenticacionTest::logout_cierra_la_sesion` | POST /logout | redirige a /login; invitado | Aprobada (CI) |
| TC-05 | Integración | `AutenticacionTest::admin_inicia_en_el_dashboard` | GET / como admin | redirige al dashboard | Aprobada (CI) |
| TC-06 | Integración | `AutenticacionTest::rutas_de_administracion_exigen_sesion` | 6 rutas sin sesión | redirige a /login | Aprobada (CI) |
| TC-07 | Integración | `CrudAdministracionTest::listados_responden_ok` | GET de 9 listados como admin | HTTP 200 | Aprobada (CI) |
| TC-08 | Integración | `CrudAdministracionTest::detalle_de_linea_y_estacion` | GET show línea y estación | HTTP 200 | Aprobada (CI) |
| TC-09 | Integración | `CrudAdministracionTest::crear_municipalidad` | nombre nuevo | registro creado | Aprobada (CI) |
| TC-10 | Integración | `CrudAdministracionTest::municipalidad_duplicada_es_rechazada` | nombre "Mixco" | error en nombre | Aprobada (CI) |
| TC-11 | Integración | `CrudAdministracionTest::crear_linea_valida` | datos válidos | registro creado | Aprobada (CI) |
| TC-12 | Integración | `CrudAdministracionTest::linea_rechaza_color_invalido` | color "rojo" | error en color | Aprobada (CI) |
| TC-13 | Integración | `CrudAdministracionTest::no_se_elimina_linea_que_dejaria_estaciones_huerfanas` | borrar Línea 13 | error; la línea sigue existiendo (Req. 2) | Aprobada (CI) |
| TC-14 | Integración | `CrudAdministracionTest::no_se_quita_estacion_que_solo_pertenece_a_una_linea` | quitar El Naranjo de Línea 13 | error; sigue con 3 estaciones | Aprobada (CI) |
| TC-15 | Integración | `CrudAdministracionTest::agregar_estacion_a_linea_desplaza_el_orden` | agregar Plaza España en pos. 2 | queda 2.ª; 5 estaciones | Aprobada (CI) |
| TC-16 | Integración | `CrudAdministracionTest::mover_estacion_intercambia_posiciones` | subir 2.ª estación | se invierten posiciones 1 y 2 | Aprobada (CI) |
| TC-17 | Integración | `CrudAdministracionTest::crear_bus_exige_parqueo_y_placa_unica` | bus válido, dos veces | crea la 1.ª; la 2.ª falla por placa | Aprobada (CI) |
| TC-18 | Integración | `CrudAdministracionTest::bus_rechaza_capacidad_fuera_de_rango` | capacidad 500 | error en capacidad_maxima | Aprobada (CI) |
| TC-19 | Integración | `CrudAdministracionTest::eliminar_bus_de_reserva` | borrar bus sin línea | bus eliminado | Aprobada (CI) |
| TC-20 | Integración | `CrudAdministracionTest::filtro_de_buses_sin_linea` | ?linea=ninguna | HTTP 200 | Aprobada (CI) |
| TC-21 | Integración | `CrudAdministracionTest::crear_estacion_valida` | datos válidos | registro creado | Aprobada (CI) |
| TC-22 | Integración (existente) | `ExampleTest::invitado_es_redirigido_al_login` | GET / sin sesión | redirige a /login | Aprobada (CI) |
| TC-23 | Integración | `PanelOperadorTest::operador_ve_el_panel_de_su_estacion` | GET panel Plaza Barrios | HTTP 200 | Aprobada (CI) |
| TC-24 | Integración | `PanelOperadorTest::validacion_de_pasajeros_negativos` | pasajeros -1 | error de validación | Aprobada (CI) |
| TC-25 | Integración | `PanelOperadorTest::operador_no_puede_registrar_en_otra_estacion` | operador en El Trébol | HTTP 403 | Aprobada (CI) |
| TC-26 | Integración | `PanelOperadorTest::registrar_visita_valida_muestra_mensaje_de_exito` | 60 pasajeros en bus de 80 | mensaje ok | Aprobada (CI) |
| TC-27 | Integración | `PanelOperadorTest::atender_alerta_la_marca_como_atendida` | admin atiende alerta | atendida = true | Aprobada (CI) |
| TC-28 | Integración | `PanelOperadorTest::operador_no_atiende_alertas_de_otra_estacion` | operador sobre alerta de El Trébol | HTTP 403 | Aprobada (CI) |
| TC-29 | Integración (existente) | `ReglasTransmetroTest::regla_5_no_permite_superar_el_doble_de_estaciones` | asignar 3 buses a Línea 18 | el 3.º es rechazado (máx. 6) | Aprobada (CI) |
| TC-30 | Integración (existente) | `ReglasTransmetroTest::regla_5_no_permite_dejar_la_linea_con_menos_buses_que_estaciones` | retirar 2 buses de Línea 13 | el 2.º es rechazado | Aprobada (CI) |
| TC-31 | Integración (existente) | `ReglasTransmetroTest::regla_7_un_bus_no_puede_quedar_sin_parqueo` | parqueo vacío | error en parqueo_id | Aprobada (CI) |
| TC-32 | Integración (existente) | `ReglasTransmetroTest::regla_14_sobrecupo_genera_alerta` | 449 y 450 en cap. 300 | alerta solo con 450 | Aprobada (CI) |
| TC-33 | Integración (existente) | `ReglasTransmetroTest::regla_15_bus_bajo_25_por_ciento_espera_5_minutos` | 19 y 20 pasajeros, cap. 80 | espera 5 y 0 min | Aprobada (CI) |
| TC-34 | Integración (existente) | `ReglasTransmetroTest::operador_solo_accede_a_su_estacion` | operador en otra estación y dashboard | HTTP 403 | Aprobada (CI) |
| TC-35 | Regresión | `RegresionTest::datos_semilla_tienen_las_cantidades_esperadas` | seeder | 4 munic., 14 est., 5 líneas, 29 buses, 15 usuarios | Aprobada (CI) |
| TC-36 | Regresión | `RegresionTest::todas_las_lineas_sembradas_cumplen_la_regla_5` | 5 líneas sembradas | todas "ok" | Aprobada (CI) |
| TC-37 | Regresión | `RegresionTest::seeder_es_idempotente` | seeder 2 veces | mismas cantidades | Aprobada (CI) |
| TC-38 | Regresión | `RegresionTest::todo_bus_tiene_parqueo` | buses sin parqueo | 0 (Req. 7) | Aprobada (CI) |
| TC-39 | Regresión | `RegresionTest::umbrales_de_negocio_no_cambian` | constantes | 1.5 / 0.25 / 5 | Aprobada (CI) |
| TC-40 | Regresión | `RegresionTest::endpoint_de_salud_responde` | GET /up | HTTP 200 | Aprobada (CI) |
| TC-41 | Integración (MySQL) | `ReportesMysqlTest::cada_reporte_se_ejecuta_y_devuelve_filas` | 4 reportes SQL en MySQL | HTTP 200 (omitida en SQLite) | Aprobada en job Test MySQL (CI); omitida en SQLite |
| TC-42 | Integración (MySQL) | `ReportesMysqlTest::reporte_inexistente_devuelve_404` | /reportes/no-existe | HTTP 404 (omitida en SQLite) | Aprobada en job Test MySQL (CI); omitida en SQLite |
| TC-43 | Unitaria | `ModelosTest::porcentaje_ocupacion_se_calcula_sobre_la_capacidad` | capacidad 200, espera 50 | 25 | Aprobada (CI) |
| TC-44 | Unitaria | `ModelosTest::porcentaje_ocupacion_con_capacidad_cero_es_cero` | capacidad 0, espera 10 | 0 (sin dividir entre cero) | Aprobada (CI) |
| TC-45 | Unitaria | `ModelosTest::buses_minimos_y_maximos_dependen_de_las_estaciones` | Línea 1 (5 estaciones) | mínimo 5, máximo 10 | Aprobada (CI) |
| TC-46 | Unitaria | `ModelosTest::distancia_total_suma_los_tramos_de_la_linea` | Línea 1: 1.2+1.5+2.0+5.5 | 10.2 km | Aprobada (CI) |
| TC-47 | Unitaria | `ModelosTest::usuario_admin_y_operador` | admin@ y operador@ | esAdmin true / false | Aprobada (CI) |
| TC-48 | Unitaria | `ReglasServicioTest::test_estado_buses_ok_cuando_cumple_el_rango` | Línea 1: 5 est., 7 buses | "ok" | Aprobada (CI) |
| TC-49 | Unitaria | `ReglasServicioTest::estado_buses_faltan_en_una_linea_nueva` | línea nueva, 1 est., 0 buses | "faltan" | Aprobada (CI) |
| TC-50 | Unitaria | `ReglasServicioTest::estado_buses_exceso` | Línea 18 con 7 buses (máx. 6) | "exceso" | Aprobada (CI) |
| TC-51 | Unitaria | `ReglasServicioTest::cambio_a_la_misma_linea_no_valida_nada` | bus a su misma línea | sin excepción | Aprobada (CI) |
| TC-52 | Unitaria | `ReglasServicioTest::quitar_estacion_rechaza_si_hay_demasiados_buses` | Línea 18, 6 buses, quitar 1 est. | ValidationException | Aprobada (CI) |
| TC-53 | Unitaria | `ReglasServicioTest::actualizar_pasajeros_no_duplica_alertas_abiertas` | 500 y luego 520 en cap. 300 | una sola alerta abierta | Aprobada (CI) |
| TC-54 | Unitaria | `ReglasServicioTest::actualizar_pasajeros_bajo_el_umbral_no_genera_alerta` | 100 en cap. 300 | sin alerta; pasajeros guardados | Aprobada (CI) |
| TC-55 | Unitaria | `ReglasServicioTest::registrar_visita_rechaza_bus_de_otra_linea` | bus de Línea 1 en Villa Hermosa | ValidationException | Aprobada (CI) |
| TC-56 | Unitaria | `ReglasServicioTest::registrar_visita_rechaza_exceso_de_capacidad` | 81 pasajeros en bus de 80 | ValidationException | Aprobada (CI) |
| TC-57 | Unitaria | `ReglasServicioTest::registrar_visita_en_el_limite_exacto_del_25_por_ciento_no_espera` | 19 y 20 pasajeros, cap. 80 | espera 5 min y 0 min | Aprobada (CI) |

## Resumen por tipo

| Tipo | Archivos |
|---|---|
| Unitaria | `tests/Unit/ModelosTest.php`, `tests/Unit/ReglasServicioTest.php` |
| Integración | `tests/Feature/AutenticacionTest.php`, `CrudAdministracionTest.php`, `PanelOperadorTest.php`, `ReglasTransmetroTest.php`, `ExampleTest.php`, `ReportesMysqlTest.php` |
| Regresión | `tests/Feature/RegresionTest.php` (datos semilla, reglas de negocio, umbrales) |

Las pruebas `ReportesMysqlTest` solo corren con MySQL (job `test-mysql` del pipeline); en SQLite se marcan como omitidas.
