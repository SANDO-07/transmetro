<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Estacion;
use App\Models\Linea;
use App\Models\Municipalidad;
use App\Models\Parqueo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de integración de las operaciones CRUD del administrador.
 */
class CrudAdministracionTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::where('rol', 'admin')->first());
    }

    public function test_listados_responden_ok(): void
    {
        foreach (['dashboard', 'lineas.index', 'estaciones.index', 'buses.index', 'pilotos.index', 'alertas.index', 'municipalidades.index', 'operadores.index', 'reportes.index'] as $ruta) {
            $this->get(route($ruta))->assertOk();
        }
    }

    public function test_detalle_de_linea_y_estacion(): void
    {
        $this->get(route('lineas.show', Linea::first()))->assertOk();
        $this->get(route('estaciones.show', Estacion::first()))->assertOk();
    }

    public function test_crear_municipalidad(): void
    {
        $this->post(route('municipalidades.store'), ['nombre' => 'Chinautla', 'departamento' => 'Guatemala'])
            ->assertRedirect(route('municipalidades.index'));
        $this->assertDatabaseHas('municipalidades', ['nombre' => 'Chinautla']);
    }

    public function test_municipalidad_duplicada_es_rechazada(): void
    {
        $this->post(route('municipalidades.store'), ['nombre' => 'Mixco', 'departamento' => 'Guatemala'])
            ->assertSessionHasErrors('nombre');
    }

    public function test_crear_linea_valida(): void
    {
        $this->post(route('lineas.store'), ['nombre' => 'Línea 99', 'color' => '#abcdef', 'municipalidad_id' => Municipalidad::first()->id])
            ->assertRedirect();
        $this->assertDatabaseHas('lineas', ['nombre' => 'Línea 99']);
    }

    public function test_linea_rechaza_color_invalido(): void
    {
        $this->post(route('lineas.store'), ['nombre' => 'Línea 98', 'color' => 'rojo', 'municipalidad_id' => Municipalidad::first()->id])
            ->assertSessionHasErrors('color');
    }

    public function test_no_se_elimina_linea_que_dejaria_estaciones_huerfanas(): void
    {
        $linea = Linea::where('nombre', 'Línea 13')->first(); // Roosevelt Mixco y El Naranjo solo están en esta línea
        $this->delete(route('lineas.destroy', $linea))->assertSessionHas('error');
        $this->assertDatabaseHas('lineas', ['id' => $linea->id]);
    }

    public function test_no_se_quita_estacion_que_solo_pertenece_a_una_linea(): void
    {
        $linea = Linea::where('nombre', 'Línea 13')->first();
        $estacion = Estacion::where('nombre', 'El Naranjo')->first();

        $this->delete(route('lineas.estaciones.quitar', [$linea, $estacion]))->assertSessionHasErrors('estacion');
        $this->assertSame(3, $linea->estaciones()->count());
    }

    public function test_agregar_estacion_a_linea_desplaza_el_orden(): void
    {
        $linea = Linea::where('nombre', 'Línea 12')->first(); // 4 estaciones
        $nueva = Estacion::where('nombre', 'Plaza España')->first();

        $this->post(route('lineas.estaciones.agregar', $linea), ['estacion_id' => $nueva->id, 'orden' => 2, 'distancia_siguiente_km' => 1.1])
            ->assertSessionHasNoErrors();

        $orden = $linea->estaciones()->get()->pluck('nombre')->all();
        $this->assertSame('Plaza España', $orden[1]);
        $this->assertCount(5, $orden);
    }

    public function test_mover_estacion_intercambia_posiciones(): void
    {
        $linea = Linea::where('nombre', 'Línea 12')->first();
        $antes = $linea->estaciones()->get()->pluck('nombre')->all();

        $this->post(route('lineas.estaciones.mover', [$linea, Estacion::where('nombre', $antes[1])->first(), 'arriba']));

        $despues = $linea->estaciones()->get()->pluck('nombre')->all();
        $this->assertSame([$antes[1], $antes[0]], [$despues[0], $despues[1]]);
    }

    public function test_crear_bus_exige_parqueo_y_placa_unica(): void
    {
        $parqueo = Parqueo::first();
        $datos = ['placa' => 'U900ZZZ', 'modelo' => 'Volvo', 'capacidad_maxima' => 100, 'parqueo_id' => $parqueo->id];

        $this->post(route('buses.store'), $datos)->assertRedirect(route('buses.index'));
        $this->assertDatabaseHas('buses', ['placa' => 'U900ZZZ']);

        $this->post(route('buses.store'), $datos)->assertSessionHasErrors('placa');
    }

    public function test_bus_rechaza_capacidad_fuera_de_rango(): void
    {
        $this->post(route('buses.store'), ['placa' => 'U901ZZZ', 'modelo' => 'X', 'capacidad_maxima' => 500, 'parqueo_id' => Parqueo::first()->id])
            ->assertSessionHasErrors('capacidad_maxima');
    }

    public function test_eliminar_bus_de_reserva(): void
    {
        $bus = Bus::whereNull('linea_id')->first();
        $this->delete(route('buses.destroy', $bus))->assertRedirect(route('buses.index'));
        $this->assertDatabaseMissing('buses', ['id' => $bus->id]);
    }

    public function test_filtro_de_buses_sin_linea(): void
    {
        $this->get(route('buses.index', ['linea' => 'ninguna']))->assertOk();
    }

    public function test_crear_estacion_valida(): void
    {
        $datos = [
            'nombre' => 'Estación Nueva', 'direccion' => 'Zona 10', 'latitud' => 14.6, 'longitud' => -90.5,
            'capacidad' => 100, 'pasajeros_espera' => 0, 'municipalidad_id' => Municipalidad::first()->id,
        ];
        $this->post(route('estaciones.store'), $datos)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('estaciones', ['nombre' => 'Estación Nueva']);
    }
}
