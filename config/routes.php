<?php
// config/routes.php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Interfaces\Controllers\AuthController;
use App\Interfaces\Controllers\UserController;
use App\Interfaces\Middleware\JwtMiddleware;

return function (App $app): void {

    // Ruta de bienvenida o raíz
    $app->get('/', function (Request $request, Response $response, array $args) {
        $response->getBody()->write("Bienvenido a la API para Huerto Urbano");
        return $response;
    });

    // Endpoints públicos
    $app->group('', function (RouteCollectorProxy $group) {
        // Health endpoint (no requiere autenticación)
        $group->get('/health', \App\Interfaces\Controllers\HealthController::class . ':health');

        // Metrics endpoint (público, para monitorización)
        $group->get('/metrics', \App\Interfaces\Controllers\MetricsController::class . ':metrics');
    });

    // Agrupación de rutas bajo el prefijo /auth
    $app->group('/auth', function (RouteCollectorProxy $group) {
        // Rutas de autenticación
        require __DIR__ . '/../routes/auth.php';
    });

    // Agrupación de rutas bajo el prefijo /api
    $app->group('/api', function (RouteCollectorProxy $group) {

        // Grupo de endpoints protegidos
        $group->group('', function (RouteCollectorProxy $protected) {
            // Ejemplo: Ruta para obtener usuarios
            require __DIR__ . '/../routes/users.php';
            // Puedes agregar otros endpoints protegidos aquí...
        })->add(new JwtMiddleware());

    });

    // Ruta para servir Swagger UI (o la documentación estática)
    $app->get('/docs[/{params:.*}]', function (Request $request, Response $response, array $args) {
        // Se asume que los archivos de Swagger UI están en public/docs/
        $file = __DIR__ . '/../public/docs/' . ($args['params'] ?? 'index.html');
        if (!file_exists($file)) {
            $response->getBody()->write('Archivo no encontrado: ' . $file);
            return $response->withStatus(404);
        }
        $fileContent = file_get_contents($file);
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $contentType = match ($ext) {
            'css' => 'text/css',
            'js'  => 'application/javascript',
            default => 'text/html'
        };
        return $response->withHeader('Content-Type', $contentType)->write($fileContent);
    });
};
