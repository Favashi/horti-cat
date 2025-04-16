<?php declare(strict_types=1);

namespace Tests\Integration;

use Tests\BaseTestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use App\Interfaces\Controllers\AuthController;

class AuthControllerTest extends BaseTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
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