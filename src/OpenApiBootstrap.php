<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        title: "Horti-Cat API para Huerto Urbano",
        version: "1.0.0",
        description: "API para la gestión y automatización de huertos urbanos"
    )
)]
#[OA\Server(
    url: "/api",
    description: "Base path para la API"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class OpenApiBootstrap
{
    // Esta clase no necesita lógica; solo sirve para cargar la documentación.
}
