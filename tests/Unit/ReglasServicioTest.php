<?php

namespace Tests\Unit;

use App\Models\Bus;
use App\Models\Estacion;
use App\Models\Linea;
use App\Services\ReglasTransmetro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * Pruebas unitarias del servicio de reglas de negocio (Req. 5, 14 y 15).
 */
class ReglasServicioTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function reglas(): ReglasTransmetro
    {
        return app(ReglasTransmetro::class);
    }

    public function test_estado_buses_ok_cuando_cumple_el_rango(): void
    {
        $linea = Linea::where('nombre', 'Línea 1')->first(); // 5 estaciones, 7 buses
        $this->assertSame('ok', $this->reglas()->estadoBuses($linea));
    }

    public function test_estado_buses_faltan_en_una_linea_nueva(): void
    {
        $linea = Linea::create(['nombre' => 'Línea X', 'color' => '#112233', 'municipalidad_id' => Linea::first()->municipalidad_id]);
        $linea->estaciones()->attach(Estacion::first()->id, ['orden' => 1, 'distancia_siguiente_km' => null]);

        $this->assertSame('faltan', $this->reglas()->estadoBuses($linea));
    }

    public function test_estado_buses_exceso(): void
    {
        $linea = Linea::where('nombre', 'Línea 18')->first(); // 3 estaciones
        Bus::whereNull('linea_id')->take(3)->update(['linea_id' => $linea->id]); // 4 + 3 = 7 > 6

        $this->assertSame('exceso', $this->reglas()->estadoBuses($linea));
    }

    public function test_cambio_a_la_misma_linea_no_valida_nada(): void
    {
        $bus = Bus::whereNotNull('linea_id')->first();
        $this->reglas()->validarCambioLineaBus($bus, $bus->linea_id);
        $this->addToAssertionCount(1);
    }

    public function test_quitar_estacion_rechaza_si_hay_demasiados_buses(): void
    {
        $linea = Linea::where('nombre', 'Línea 18')->first(); // 3 estaciones, 4 buses
        Bus::whereNull('linea_id')->take(2)->update(['linea_id' => $linea->id]); // 6 buses; con 2 estaciones el máximo es 4

        $this->expectException(ValidationException::class);
        $this->reglas()->validarQuitarEstacion($linea);
    }

    public function test_actualizar_pasajeros_no_duplica_alertas_abiertas(): void
    {
        $estacion = Estacion::where('nombre', 'Plaza Barrios')->first(); // capacidad 300

        $a = $this->reglas()->actualizarPasajeros($estacion, 500);
        $b = $this->reglas()->actualizarPasajeros($estacion, 520);

        $this->assertNotNull($a);
        $this->assertSame($a->id, $b->id);
        $this->assertSame(1, $estacion->alertas()->where('tipo', 'sobrecupo')->count());
    }

    public function test_actualizar_pasajeros_bajo_el_umbral_no_genera_alerta(): void
    {
        $estacion = Estacion::where('nombre', 'Plaza Barrios')->first();
        $this->assertNull($this->reglas()->actualizarPasajeros($estacion, 100));
        $this->assertSame(100, $estacion->fresh()->pasajeros_espera);
    }

    public function test_registrar_visita_rechaza_bus_de_otra_linea(): void
    {
        $estacion = Estacion::where('nombre', 'Villa Hermosa')->first(); // solo Línea 18
        $bus = Bus::whereHas('linea', fn ($q) => $q->where('nombre', 'Línea 1'))->first();

        $this->expectException(ValidationException::class);
        $this->reglas()->registrarVisita($bus, $estacion, 10);
    }

    public function test_registrar_visita_rechaza_exceso_de_capacidad(): void
    {
        $estacion = Estacion::where('nombre', 'Plaza Barrios')->first();
        $bus = Bus::where('placa', 'U100BCD')->first(); // Línea 1, capacidad 80

        $this->expectException(ValidationException::class);
        $this->reglas()->registrarVisita($bus, $estacion, 81);
    }

    public function test_registrar_visita_en_el_limite_exacto_del_25_por_ciento_no_espera(): void
    {
        $estacion = Estacion::where('nombre', 'Plaza Barrios')->first();
        $bus = Bus::where('placa', 'U100BCD')->first(); // capacidad 80 -> 25 % = 20

        $this->assertSame(5, $this->reglas()->registrarVisita($bus, $estacion, 19)->espera_extra_min);
        $this->assertSame(0, $this->reglas()->registrarVisita($bus, $estacion, 20)->espera_extra_min);
    }
}
