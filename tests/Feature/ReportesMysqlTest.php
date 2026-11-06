<?php

namespace Tests\Feature;

use App\Http\Controllers\ReporteController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

/**
 * Los reportes usan SQL específico de MySQL, por lo que solo se ejecutan cuando la
 * conexión de pruebas es MySQL (job "test-mysql" del pipeline). En SQLite se omiten.
 */
#[Group('mysql')]
class ReportesMysqlTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDriverName() !== 'mysql') {
            $this->markTestSkipped('Requiere MySQL.');
        }
    }

    public function test_cada_reporte_se_ejecuta_y_devuelve_filas(): void
    {
        $this->actingAs(User::where('rol', 'admin')->first());

        foreach (array_keys(ReporteController::REPORTES) as $clave) {
            $this->get(route('reportes.show', $clave))->assertOk();
        }
    }

    public function test_reporte_inexistente_devuelve_404(): void
    {
        $this->actingAs(User::where('rol', 'admin')->first())->get('/reportes/no-existe')->assertNotFound();
    }
}
