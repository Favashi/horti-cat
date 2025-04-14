<?php declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use App\Domain\Repository\UserRepositoryInterface;
use App\Application\UseCase\Auth\GenerateTokenServiceInterface;
use App\Interfaces\Controllers\AuthController;
use App\Application\UseCase\Auth\AuthenticateUser;
use App\Domain\Entity\User;

class AuthControllerTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        // Configura el contenedor para test con implementaciones dummy
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            UserRepositoryInterface::class => function () {
                // Utilizamos un hash fijo para asegurar la consistencia en tests.
                $fixedHash = '$2y$10$Wjht8DQ3F3Y.z3XgGJX1.eKw1Kz91LZZySXS6lpqU/EX9Mlu3Y1lm';
                // Devuelve un usuario para el username "admin"
                return new class($fixedHash) implements UserRepositoryInterface {
                    private $fixedHash;
                    public function __construct($fixedHash) {
                        $this->fixedHash = $fixedHash;
                    }
                    public function findByUsername(string $username): ?User {
                        if ($username === 'admin') {
                            return new User('1', 'admin', $this->fixedHash);
                        }
                        return null;
                    }
                    public function getAllUsers(): array {
                        return [];
                    }
                };
            },
            GenerateTokenServiceInterface::class => function () {
                return new class implements GenerateTokenServiceInterface {
                    public function generateToken(array $claims): string {
                        return 'dummy-token';
                    }
                };
            },
        ]);
        $container = $containerBuilder->build();

        // Configura Slim usando el contenedor
        AppFactory::setContainer($container);
        $this->app = AppFactory::create();

        // Registra la ruta de login
        $this->app->post('/auth/login', AuthController::class . ':login');
    }

    public function testLoginReturnsTokenForValidCredentials(): void
    {
        $body = json_encode(['username' => 'admin', 'password' => 'secret']);
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/auth/login')
            ->withHeader('Content-Type', 'application/json');
        $stream = (new StreamFactory())->createStream($body);
        $request = $request->withBody($stream);

        $response = $this->app->handle($request);
        $this->assertEquals(200, $response->getStatusCode(), "El status code debe ser 200 para credenciales válidas");

        $data = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('token', $data, "La respuesta debe contener el token");
        $this->assertEquals('dummy-token', $data['token'], "El token retornado debe ser 'dummy-token'");
    }

    public function testLoginReturns401ForInvalidCredentials(): void
    {
        $body = json_encode(['username' => 'admin', 'password' => 'wrongpassword']);
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/auth/login')
            ->withHeader('Content-Type', 'application/json');
        $stream = (new StreamFactory())->createStream($body);
        $request = $request->withBody($stream);

        $response = $this->app->handle($request);
        $this->assertEquals(401, $response->getStatusCode(), "El status code debe ser 401 para credenciales inválidas");
    }
}