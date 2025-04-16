<?php declare(strict_types=1);

namespace Tests\Integration;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\BaseTestCase;
use App\Interfaces\Controllers\MetricsController;

class MetricsEndpointTest extends BaseTestCase
{
    protected function setUp(): void {

       parent::setUp();
       $this->app->get('/metrics', MetricsController::class . ':metrics');

    }

    public function testMetricsEndpointReturnsText(): void {
        // Crea una petición GET para la ruta /metrics
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/metrics');
        // Maneja la petición y obtiene la respuesta
        $response = $this->app->handle($request);

        // Verifica que se reciba un status code 200
        $this->assertEquals(200, $response->getStatusCode(), 'El status code debe ser 200 para el endpoint de métricas');

        // Verifica que el Content-Type sea text/plain
        $contentType = $response->getHeaderLine('Content-Type');
        $this->assertStringContainsString('text/plain', $contentType, 'El Content-Type debe ser text/plain');

        // Comprueba que el cuerpo de la respuesta contenga las líneas clave de las métricas
        $body = (string)$response->getBody();
        $this->assertStringContainsString('# HELP app_requests_total', $body, 'El reporte debe contener la métrica app_requests_total');
        $this->assertStringContainsString('# TYPE app_requests_total counter', $body, 'El reporte debe definir el tipo de la métrica app_requests_total');
        $this->assertStringContainsString('# HELP app_response_time_seconds', $body, 'El reporte debe contener la métrica app_response_time_seconds');
        $this->assertStringContainsString('# TYPE app_response_time_seconds gauge', $body, 'El reporte debe definir el tipo de la métrica app_response_time_seconds');
    }
}
