# Matriz de Pruebas

Casos de prueba automatizados del proyecto Transmetro (PHPUnit). La columna **Resultado obtenido** se completa con la salida real de `vendor/bin/phpunit --testdox` (ver `docs/evidencias/`) o de la ejecución en GitHub Actions.

Estado de ejecución: `Pendiente de ejecutar` hasta correr el pipeline por primera vez. Reemplazar por `Aprobada` / `Fallida` y adjuntar la captura correspondiente.

| ID | Tipo | Prueba | Entrada | Resultado esperado | Resultado obtenido |
|---|---|---|---|---|---|
| TC-01 | Integración | `AutenticacionTest::login_correcto_del_admin_redirige_al_inicio` | credenciales admin válidas | redirige a / y queda autenticado | Pendiente de ejecutar |
| TC-02 | Integración | `AutenticacionTest::login_con_contrasena_incorrecta_falla` | contraseña errónea | error en email; invitado | Pendiente de ejecutar |
| TC-03 | Integración | `AutenticacionTest::login_exige_correo_valido` | email "no-es-correo" | error de validación | Pendiente de ejecutar |
| TC-04 | Integración | `AutenticacionTest::logout_cierra_la_sesion` | POST /logout | redirige a /login; invitado | Pendiente de ejecutar |
| TC-05 | Integración | `AutenticacionTest::admin_inicia_en_el_dashboard` | GET / como admin | redirige al dashboard | Pendiente de ejecutar |
| TC-06 | Integración | `AutenticacionTest::rutas_de_administracion_exigen_sesion` | 6 rutas sin sesión | redirige a /login | Pendiente de ejecutar |
| TC-07 | Integración | `CrudAdministracionTest::listados_responden_ok` | GET de 9 listados como admin | HTTP 200 | Pendiente de ejecutar |
| TC-08 | Integración | `CrudAdministracionTest::detalle_de_linea_y_estacion` | GET show línea y estación | HTTP 200 | Pendiente de ejecutar |
| TC-09 | Integración | `CrudAdministracionTest::crear_municipalidad` | nombre nuevo | registro creado | Pendiente de ejecutar |
| TC-10 | Integración | `CrudAdministracionTest::municipalidad_duplicada_es_rechazada` | nombre "Mixco" | error en nombre | Pendiente de ejecutar |
| TC-11 | Integración | `CrudAdministracionTest::crear_linea_valida` | datos válidos | registro creado | Pendiente de ejecutar |
| TC-12 | Integración | `CrudAdministracionTest::linea_rechaza_color_invalido` | color "rojo" | error en color | Pendiente de ejecutar |
| TC-13 | Integración | `CrudAdministracionTest::no_se_elimina_linea_que_dejaria_estaciones_huerfanas` | borrar Línea 13 | error; la línea sigue existiendo (Req. 2) | Pendiente de ejecutar |
| TC-14 | Integración | `CrudAdministracionTest::no_se_quita_estacion_que_solo_pertenece_a_una_linea` | quitar El Naranjo de Línea 13 | error; sigue con 3 estaciones | Pendiente de ejecutar |
| TC-15 | Integración | `CrudAdministracionTest::agregar_estacion_a_linea_desplaza_el_orden` | agregar Plaza España en pos. 2 | queda 2.ª; 5 estaciones | Pendiente de ejecutar |
| TC-16 | Integración | `CrudAdministracionTest::mover_estacion_intercambia_posiciones` | subir 2.ª estación | se invierten posiciones 1 y 2 | Pendiente de ejecutar |
| TC-17 | Integración | `CrudAdministracionTest::crear_bus_exige_parqueo_y_placa_unica` | bus válido, dos veces | crea la 1.ª; la 2.ª falla por placa | Pendiente de ejecutar |
| TC-18 | Integración | `CrudAdministracionTest::bus_rechaza_capacidad_fuera_de_rango` | capacidad 500 | error en capacidad_maxima | Pendiente de ejecutar |
| TC-19 | Integración | `CrudAdministracionTest::eliminar_bus_de_reserva` | borrar bus sin línea | bus eliminado | Pendiente de ejecutar |
| TC-20 | Integración | `CrudAdministracionTest::filtro_de_buses_sin_linea` | ?linea=ninguna | HTTP 200 | Pendiente de ejecutar |
| TC-21 | Integración | `CrudAdministracionTest::crear_estacion_valida` | datos válidos | registro creado | Pendiente de ejecutar |
| TC-22 | Integración (existente) | `ExampleTest::invitado_es_redirigido_al_login` | GET / sin sesión | redirige a /login | Pendiente de ejecutar |
| TC-23 | Integración | `PanelOperadorTest::operador_ve_el_panel_de_su_estacion` | GET panel Plaza Barrios | HTTP 200 | Pendiente de ejecutar |
| TC-24 | Integración | `PanelOperadorTest::validacion_de_pasajeros_negativos` | pasajeros -1 | error de validación | Pendiente de ejecutar |
| TC-25 | Integración | `PanelOperadorTest::operador_no_puede_registrar_en_otra_estacion` | operador en El Trébol | HTTP 403 | Pendiente de ejecutar |
| TC-26 | Integración | `PanelOperadorTest::registrar_visita_valida_muestra_mensaje_de_exito` | 60 pasajeros en bus de 80 | mensaje ok | Pendiente de ejecutar |
| TC-27 | Integración | `PanelOperadorTest::atender_alerta_la_marca_como_atendida` | admin atiende alerta | atendida = true | Pendiente de ejecutar |
| TC-28 | Integración | `PanelOperadorTest::operador_no_atiende_alertas_de_otra_estacion` | operador sobre alerta de El Trébol | HTTP 403 | Pendiente de ejecutar |
| TC-29 | Integración (existente) | `ReglasTransmetroTest::regla_5_no_permite_superar_el_doble_de_estaciones` | asignar 3 buses a Línea 18 | el 3.º es rechazado (máx. 6) | Pendiente de ejecutar |
| TC-30 | Integración (existente) | `ReglasTransmetroTest::regla_5_no_permite_dejar_la_linea_con_menos_buses_que_estaciones` | retirar 2 buses de Línea 13 | el 2.º es rechazado | Pendiente de ejecutar |
| TC-31 | Integración (existente) | `ReglasTransmetroTest::regla_7_un_bus_no_puede_quedar_sin_parqueo` | parqueo vacío | error en parqueo_id | Pendiente de ejecutar |
| TC-32 | Integración (existente) | `ReglasTransmetroTest::regla_14_sobrecupo_genera_alerta` | 449 y 450 en cap. 300 | alerta solo con 450 | Pendiente de ejecutar |
| TC-33 | Integración (existente) | `ReglasTransmetroTest::regla_15_bus_bajo_25_por_ciento_espera_5_minutos` | 19 y 20 pasajeros, cap. 80 | espera 5 y 0 min | Pendiente de ejecutar |
| TC-34 | Integración (existente) | `ReglasTransmetroTest::operador_solo_accede_a_su_estacion` | operador en otra estación y dashboard | HTTP 403 | Pendiente de ejecutar |
| TC-35 | Regresión | `RegresionTest::datos_semilla_tienen_las_cantidades_esperadas` | seeder | 4 munic., 14 est., 5 líneas, 29 buses, 15 usuarios | Pendiente de ejecutar |
| TC-36 | Regresión | `RegresionTest::todas_las_lineas_sembradas_cumplen_la_regla_5` | 5 líneas sembradas | todas "ok" | Pendiente de ejecutar |
| TC-37 | Regresión | `RegresionTest::seeder_es_idempotente` | seeder 2 veces | mismas cantidades | Pendiente de ejecutar |
| TC-38 | Regresión | `RegresionTest::todo_bus_tiene_parqueo` | buses sin parqueo | 0 (Req. 7) | Pendiente de ejecutar |
| TC-39 | Regresión | `RegresionTest::umbrales_de_negocio_no_cambian` | constantes | 1.5 / 0.25 / 5 | Pendiente de ejecutar |
| TC-40 | Regresión | `RegresionTest::endpoint_de_salud_responde` | GET /up | HTTP 200 | Pendiente de ejecutar |
| TC-41 | Integración (MySQL) | `ReportesMysqlTest::cada_reporte_se_ejecuta_y_devuelve_filas` | 4 reportes SQL en MySQL | HTTP 200 (omitida en SQLite) | Pendiente de ejecutar |
| TC-42 | Integración (MySQL) | `ReportesMysqlTest::reporte_inexistente_devuelve_404` | /reportes/no-existe | HTTP 404 (omitida en SQLite) | Pendiente de ejecutar |
| TC-43 | Unitaria | `ModelosTest::porcentaje_ocupacion_se_calcula_sobre_la_capacidad` | capacidad 200, espera 50 | 25 | Pendiente de ejecutar |
| TC-44 | Unitaria | `ModelosTest::porcentaje_ocupacion_con_capacidad_cero_es_cero` | capacidad 0, espera 10 | 0 (sin dividir entre cero) | Pendiente de ejecutar |
| TC-45 | Unitaria | `ModelosTest::buses_minimos_y_maximos_dependen_de_las_estaciones` | Línea 1 (5 estaciones) | mínimo 5, máximo 10 | Pendiente de ejecutar |
| TC-46 | Unitaria | `ModelosTest::distancia_total_suma_los_tramos_de_la_linea` | Línea 1: 1.2+1.5+2.0+5.5 | 10.2 km | Pendiente de ejecutar |
| TC-47 | Unitaria | `ModelosTest::usuario_admin_y_operador` | admin@ y operador@ | esAdmin true / false | Pendiente de ejecutar |
| TC-48 | Unitaria | `ReglasServicioTest::estado_buses_ok_cuando_cumple_el_rango` | Línea 1: 5 est., 7 buses | "ok" | Pendiente de ejecutar |
| TC-49 | Unitaria | `ReglasServicioTest::estado_buses_faltan_en_una_linea_nueva` | línea nueva, 1 est., 0 buses | "faltan" | Pendiente de ejecutar |
| TC-50 | Unitaria | `ReglasServicioTest::estado_buses_exceso` | Línea 18 con 7 buses (máx. 6) | "exceso" | Pendiente de ejecutar |
| TC-51 | Unitaria | `ReglasServicioTest::cambio_a_la_misma_linea_no_valida_nada` | bus a su misma línea | sin excepción | Pendiente de ejecutar |
| TC-52 | Unitaria | `ReglasServicioTest::quitar_estacion_rechaza_si_hay_demasiados_buses` | Línea 18, 6 buses, quitar 1 est. | ValidationException | Pendiente de ejecutar |
| TC-53 | Unitaria | `ReglasServicioTest::actualizar_pasajeros_no_duplica_alertas_abiertas` | 500 y luego 520 en cap. 300 | una sola alerta abierta | Pendiente de ejecutar |
| TC-54 | Unitaria | `ReglasServicioTest::actualizar_pasajeros_bajo_el_umbral_no_genera_alerta` | 100 en cap. 300 | sin alerta; pasajeros guardados | Pendiente de ejecutar |
| TC-55 | Unitaria | `ReglasServicioTest::registrar_visita_rechaza_bus_de_otra_linea` | bus de Línea 1 en Villa Hermosa | ValidationException | Pendiente de ejecutar |
| TC-56 | Unitaria | `ReglasServicioTest::registrar_visita_rechaza_exceso_de_capacidad` | 81 pasajeros en bus de 80 | ValidationException | Pendiente de ejecutar |
| TC-57 | Unitaria | `ReglasServicioTest::registrar_visita_en_el_limite_exacto_del_25_por_ciento_no_espera` | 19 y 20 pasajeros, cap. 80 | espera 5 min y 0 min | Pendiente de ejecutar |

## Resumen por tipo

| Tipo | Archivos |
|---|---|
| Unitaria | `tests/Unit/ModelosTest.php`, `tests/Unit/ReglasServicioTest.php` |
| Integración | `tests/Feature/AutenticacionTest.php`, `CrudAdministracionTest.php`, `PanelOperadorTest.php`, `ReglasTransmetroTest.php`, `ExampleTest.php`, `ReportesMysqlTest.php` |
| Regresión | `tests/Feature/RegresionTest.php` (datos semilla, reglas de negocio, umbrales) |

Las pruebas `ReportesMysqlTest` solo corren con MySQL (job `test-mysql` del pipeline); en SQLite se marcan como omitidas.
