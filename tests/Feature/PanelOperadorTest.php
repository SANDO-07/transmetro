<?php

namespace Tests\Feature;

use App\Models\Alerta;
use App\Models\Bus;
use App\Models\Estacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de integración del panel de estación (Req. 14, 15 y 16).
 */
class PanelOperadorTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function operador(): User
    {
        return User::where('email', 'operador@transmetro.gt')->first();
    }

    public function test_operador_ve_el_panel_de_su_estacion(): void
    {
        $estacion = Estacion::where('nombre', 'Plaza Barrios')->first();
        $this->actingAs($this->operador())->get(route('panel.show', $estacion))->assertOk();
    }

    public function test_validacion_de_pasajeros_negativos(): void
    {
        $estacion = Estacion::where('nombre', 'Plaza Barrios')->first();
        $this->actingAs($this->operador())
            ->post(route('panel.pasajeros', $estacion), ['pasajeros_espera' => -1])
            ->assertSessionHasErrors('pasajeros_espera');
    }

    public function test_operador_no_puede_registrar_en_otra_estacion(): void
    {
        $otra = Estacion::where('nombre', 'El Trébol')->first();
        $this->actingAs($this->operador())
            ->post(route('panel.pasajeros', $otra), ['pasajeros_espera' => 10])
            ->assertForbidden();
    }

    public function test_registrar_visita_valida_muestra_mensaje_de_exito(): void
    {
        $estacion = Estacion::where('nombre', 'Plaza Barrios')->first();
        $bus = Bus::where('placa', 'U100BCD')->first();

        $this->actingAs($this->operador())
            ->post(route('panel.visita', $estacion), ['bus_id' => $bus->id, 'pasajeros_abordo' => 60])
            ->assertSessionHas('ok');
    }

    public function test_atender_alerta_la_marca_como_atendida(): void
    {
        $alerta = Alerta::where('atendida', false)->first(); // El Trébol (sobrecupo sembrado)
        $admin = User::where('rol', 'admin')->first();

        $this->actingAs($admin)->patch(route('alertas.atender', $alerta))->assertSessionHas('ok');
        $this->assertTrue($alerta->fresh()->atendida);
    }

    public function test_operador_no_atiende_alertas_de_otra_estacion(): void
    {
        $alerta = Alerta::where('atendida', false)->first(); // pertenece a El Trébol
        $this->actingAs($this->operador())->patch(route('alertas.atender', $alerta))->assertForbidden();
    }
}
