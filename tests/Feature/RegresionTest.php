<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Estacion;
use App\Models\Linea;
use App\Models\Municipalidad;
use App\Models\User;
use App\Services\ReglasTransmetro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de regresión: fijan el estado esperado de los datos semilla y las reglas
 * ya corregidas, para detectar si un cambio futuro las rompe.
 */
class RegresionTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_datos_semilla_tienen_las_cantidades_esperadas(): void
    {
        $this->assertSame(4, Municipalidad::count());
        $this->assertSame(14, Estacion::count());
        $this->assertSame(5, Linea::count());
        $this->assertSame(29, Bus::count()); // 7 + 6 + 5 + 4 + 4 + 3 de reserva
        $this->assertSame(15, User::count()); // 1 admin + 14 operadores
    }

    public function test_todas_las_lineas_sembradas_cumplen_la_regla_5(): void
    {
        $reglas = app(ReglasTransmetro::class);
        foreach (Linea::all() as $linea) {
            $this->assertSame('ok', $reglas->estadoBuses($linea), $linea->nombre);
        }
    }

    public function test_seeder_es_idempotente(): void
    {
        $this->seed();
        $this->assertSame(4, Municipalidad::count());
        $this->assertSame(29, Bus::count());
    }

    public function test_todo_bus_tiene_parqueo(): void
    {
        $this->assertSame(0, Bus::whereNull('parqueo_id')->count());
    }

    public function test_umbrales_de_negocio_no_cambian(): void
    {
        $this->assertSame(1.5, ReglasTransmetro::UMBRAL_ALERTA);
        $this->assertSame(0.25, ReglasTransmetro::UMBRAL_OCUPACION_BUS);
        $this->assertSame(5, ReglasTransmetro::ESPERA_EXTRA_MIN);
    }

    public function test_endpoint_de_salud_responde(): void
    {
        $this->get('/up')->assertOk();
    }
}
