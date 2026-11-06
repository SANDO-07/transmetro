<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de integración: autenticación y control de acceso por rol.
 */
class AutenticacionTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_login_correcto_del_admin_redirige_al_inicio(): void
    {
        $this->post('/login', ['email' => 'admin@transmetro.gt', 'password' => 'admin123'])->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_login_con_contrasena_incorrecta_falla(): void
    {
        $this->post('/login', ['email' => 'admin@transmetro.gt', 'password' => 'incorrecta'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_exige_correo_valido(): void
    {
        $this->post('/login', ['email' => 'no-es-correo', 'password' => 'x'])->assertSessionHasErrors('email');
    }

    public function test_logout_cierra_la_sesion(): void
    {
        $this->post('/login', ['email' => 'admin@transmetro.gt', 'password' => 'admin123']);
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_admin_inicia_en_el_dashboard(): void
    {
        $this->post('/login', ['email' => 'admin@transmetro.gt', 'password' => 'admin123']);
        $this->get('/')->assertRedirect(route('dashboard'));
    }

    public function test_rutas_de_administracion_exigen_sesion(): void
    {
        foreach (['/dashboard', '/lineas', '/estaciones', '/buses', '/pilotos', '/alertas'] as $ruta) {
            $this->get($ruta)->assertRedirect('/login');
        }
    }
}
