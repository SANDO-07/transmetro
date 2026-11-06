<?php

namespace Tests\Unit;

use App\Models\Estacion;
use App\Models\Linea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas unitarias de la lógica contenida en los modelos.
 */
class ModelosTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_porcentaje_ocupacion_se_calcula_sobre_la_capacidad(): void
    {
        $estacion = new Estacion(['capacidad' => 200, 'pasajeros_espera' => 50]);
        $this->assertSame(25, $estacion->porcentajeOcupacion());
    }

    public function test_porcentaje_ocupacion_con_capacidad_cero_es_cero(): void
    {
        $estacion = new Estacion(['capacidad' => 0, 'pasajeros_espera' => 10]);
        $this->assertSame(0, $estacion->porcentajeOcupacion());
    }

    public function test_buses_minimos_y_maximos_dependen_de_las_estaciones(): void
    {
        $linea = Linea::where('nombre', 'Línea 1')->first(); // 5 estaciones
        $this->assertSame(5, $linea->busesMinimos());
        $this->assertSame(10, $linea->busesMaximos());
    }

    public function test_distancia_total_suma_los_tramos_de_la_linea(): void
    {
        $linea = Linea::where('nombre', 'Línea 1')->first(); // 1.2 + 1.5 + 2.0 + 5.5
        $this->assertEqualsWithDelta(10.2, $linea->distanciaTotal(), 0.001);
    }

    public function test_usuario_admin_y_operador(): void
    {
        $this->assertTrue(User::where('email', 'admin@transmetro.gt')->first()->esAdmin());
        $this->assertFalse(User::where('email', 'operador@transmetro.gt')->first()->esAdmin());
    }
}
