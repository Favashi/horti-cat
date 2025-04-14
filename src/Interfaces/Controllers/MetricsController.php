<?php declare(strict_types=1);

namespace App\Interfaces\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/metrics",
    summary: "Exposición de métricas de la API en formato Prometheus",
    tags: ["Metrics"],
    servers: [ new OA\Server(url: "/") ],
    responses: [
        new OA\Response(
            response: "200",
            description: "Métricas en formato Prometheus",
            content: new OA\MediaType(
                mediaType: "text/plain"
            )
        )
    ]
)]
class MetricsController
{
    public function metrics(Request $request, Response $response, array $args): Response {
        // TODO: Añadir librería de metricas como promphp/prometheus_client_php
        $metrics = <<<EOT
            # HELP app_requests_total Número total de peticiones
            # TYPE app_requests_total counter
            app_requests_total 123
            
            # HELP app_response_time_seconds Tiempo de respuesta en segundos
            # TYPE app_response_time_seconds gauge
            app_response_time_seconds 0.256
        EOT;
        $response->getBody()->write($metrics);
        return $response->withHeader('Content-Type', 'text/plain');
    }
}