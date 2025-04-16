<?php declare(strict_types=1);

namespace Tests\Integration;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\BaseTestCase;
use App\Interfaces\Controllers\HealthController;

class HealthEndpointTest extends BaseTestCase
{

    protected function setUp(): void {
        parent::setUp();
        // Registra la ruta de Health apuntando al HealthController
        $this->app->get('/health', HealthController::class . ':health');
    }

    public function testHealthEndpointReturnsOK(): void {
        // Crea una petición GET para la ruta /health
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/health');

        // Maneja la petición a través de la aplicación Slim
        $response = $this->app->handle($request);

        // Verifica que la respuesta tenga un status code 200
        $this->assertEquals(200, $response->getStatusCode(), 'El status code debe ser 200');

        // Verifica que el Content-Type es application/json
        $contentType = $response->getHeaderLine('Content-Type');
        $this->assertStringContainsString('application/json', $contentType, 'El Content-Type debe ser application/json');

        // Decodifica el cuerpo de la respuesta y verifica los datos devueltos
        $data = json_decode((string)$response->getBody(), true);
        $this->assertEquals('OK', $data['status'], 'El status devuelto debe ser "OK"');
        $this->assertIsInt($data['timestamp'], 'El timestamp debe ser un entero');
    }
}
