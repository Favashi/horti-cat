<?php declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Crear contenedor DI y cargar dependencias
$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../config/dependencies.php')($containerBuilder);
$container = $containerBuilder->build();

// Configurar Slim para usar el contenedor
AppFactory::setContainer($container);
$app = AppFactory::create();

// Agregar middlewares globales
(require __DIR__ . '/../config/middleware.php')($app);

// Registrar rutas (se delega a archivos modulares)
(require __DIR__ . '/../config/routes.php')($app);

// Ejecutar la aplicación
$app->run();
