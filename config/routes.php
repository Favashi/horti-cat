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
    $app->group('/api/v1', function (RouteCollectorProxy $group) {

        // Grupo de endpoints protegidos
        $group->group('', function (RouteCollectorProxy $protected) {
            // Ejemplo: Ruta para obtener usuarios
            require __DIR__ . '/../routes/users.php';
            // Puedes agregar otros endpoints protegidos aquí...
        })->add(new JwtMiddleware());

    });

    // Ruta para devolver el JSON de la documentación OpenAPI
    $app->get('/docs/swagger.json', function ($request, $response, $args) {
        // Obtenemos el objeto URI de la petición entrante. Esto nos permite extraer el esquema, host y puerto.
        $uri = $request->getUri();

        // Construimos la URL base a partir del esquema, host y (si existe) puerto.
        $baseUrl = $uri->getScheme() . '://' . $uri->getHost();
        if ($uri->getPort()) {
            $baseUrl .= ':' . $uri->getPort();
        }

        // Determina el path base de tu API (por ejemplo, '/api' o '/api/v1')
        // Esto lo puedes ajustar según la configuración de tu proyecto
        $apiBasePath = '/api/v1';

        // Combina la URL base con el path base de la API
        $serverUrl = $baseUrl . $apiBasePath;

        // Escanea los atributos en tu proyecto para generar la documentación OpenAPI.
        // La ruta puede ajustarse según dónde estén ubicadas tus clases
        $openapi = \OpenApi\Generator::scan([__DIR__ . '/../src']);

        // Actualizamos dinámicamente el objeto OpenAPI para que use la URL generada
        $openapi->servers = [
            (object)[
                'url' => $serverUrl,
                'description' => 'Servidor generado automáticamente según el host de la petición'
            ]
        ];

        // Prepara el JSON de la documentación y establece el header de la respuesta
        $json = $openapi->toJson();
        $response->getBody()->write($json);
        return $response->withHeader('Content-Type', 'application/json');
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

    //Ruta para serviro CodeCoverage
    $app->get('/coverage', function (Request $request, Response $response, array $args) {
        // Se asume que los archivos de Swagger UI están en public/docs/
        $file = __DIR__ . '/../public/coverage/index.html';
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
