<?php declare(strict_types=1);

namespace App\Interfaces\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/health",
    summary: "Verifica el estado de la API",
    tags: ["Health"],
    servers: [ new OA\Server(url: "/") ],
    responses: [
        new OA\Response(
            response: "200",
            description: "La API está en funcionamiento",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "status", type: "string", example: "OK"),
                    new OA\Property(property: "timestamp", type: "integer", example: 1670000000)
                ]
            )
        )
    ]
)]
class HealthController
{
    public function health(Request $request, Response $response, array $args): Response {
        $data = [
            'status' => 'OK',
            'timestamp' => time()
            // Puedes agregar información adicional, por ejemplo, versión, uptime, etc.
        ];
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}