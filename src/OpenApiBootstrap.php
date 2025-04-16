<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        version: "1.0.0",
        description: "API para la gestión y automatización de huertos urbanos",
        title: "Horti-Cat API para Huerto Urbano"
    )
)]
#[OA\Server(
    url: "/api",
    description: "Base path para la API",
    variables: [
        new OA\ServerVariable("version", "version","v1", ["v1", "v2"])
    ]
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    bearerFormat: "JWT",
    scheme: "bearer"
)]
class OpenApiBootstrap
{
    // Esta clase no necesita lógica; solo sirve para cargar la documentación.
}
