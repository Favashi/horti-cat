<?php
// config/middleware.php

use Slim\App;

return function (App $app): void {
    // Agrega middleware de ruteo
    $app->addRoutingMiddleware();

    // Middleware para parsear el body de las peticiones (JSON, XML, etc.)
    $app->addBodyParsingMiddleware();

    // Middleware para manejo de errores (en desarrollo: true, en producción: false)
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    // Puedes agregar otros middlewares, por ejemplo, CORS, autenticación, etc.
    // $app->add(new \App\Interfaces\Middleware\JwtMiddleware());
};