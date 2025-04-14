<?php declare(strict_types=1);

namespace Tests\Unit\Application\UseCase\Auth;

use PHPUnit\Framework\TestCase;
use App\Application\UseCase\Auth\AuthenticateUser;
use App\Application\UseCase\Auth\GenerateTokenServiceInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Entity\User;

class AuthenticateUserTest extends TestCase
{
    private $userRepositoryMock;
    private $tokenServiceMock;

    protected function setUp(): void
    {
        // Creamos un mock para el repositorio de usuario
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        // Creamos un mock para el servicio de token
        $this->tokenServiceMock = $this->createMock(GenerateTokenServiceInterface::class);
    }

    public function testAuthenticateValidCredentials(): void
    {
        // Preparamos los datos dummy para el usuario
        $fixedHash = '$2y$10$Wjht8DQ3F3Y.z3XgGJX1.eKw1Kz91LZZySXS6lpqU/EX9Mlu3Y1lm';
        $dummyUser = new User('1', 'admin', $fixedHash);

        // Configuramos el mock del repositorio para que retorne el usuario cuando se le solicite "admin"
        $this->userRepositoryMock->expects($this->once())
            ->method('findByUsername')
            ->with('admin')
            ->willReturn($dummyUser);

        // Configuramos el mock del servicio de token para que genere el token esperado
        $this->tokenServiceMock->expects($this->once())
            ->method('generateToken')
            ->with($this->callback(function ($claims) {
                return isset($claims['sub'], $claims['username'])
                    && $claims['sub'] === '1'
                    && $claims['username'] === 'admin';
            }))
            ->willReturn('dummy-token');

        // Creamos el caso de uso con los mocks inyectados
        $authenticateUser = new AuthenticateUser($this->userRepositoryMock, $this->tokenServiceMock);

        $token = $authenticateUser->execute('admin', 'secret');
        $this->assertEquals('dummy-token', $token);
    }

    public function testAuthenticateInvalidCredentialsThrowsException(): void
    {
        $this->expectException(\Exception::class);
        // Cuando se busca el usuario "admin", devolvemos null para simular credenciales inválidas
        $this->userRepositoryMock->expects($this->once())
            ->method('findByUsername')
            ->with('admin')
            ->willReturn(null);

        // No es necesario configurar el tokenService en este test
        $authenticateUser = new AuthenticateUser($this->userRepositoryMock, $this->tokenServiceMock);
        $authenticateUser->execute('admin', 'wrongpassword');
    }
}