<?php declare(strict_types=1);

namespace Tests;

use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Factory\AppFactory;

abstract class BaseTestCase extends TestCase
{
    /** @var App */
    protected $app;

    protected function setUp(): void
    {
        // Configura el contenedor DI centralizado
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            // Definiciones comunes para tests, por ejemplo:
            \App\Domain\Repository\UserRepositoryInterface::class => function () {

                // Usa un hash fijo precomputado para "secret"
                $fixedHash = '$2y$10$oZJ3mXKZOZsxWQBM7iiKHetS.XzBDH9QRaSg8mC.ZntSpCc0vDtoa';

                return new class($fixedHash) implements \App\Domain\Repository\UserRepositoryInterface {
                    private $fixedHash;
                    public function __construct(string $fixedHash) {
                        $this->fixedHash = $fixedHash;
                    }
                    public function findByUsername(string $username): ?\App\Domain\Entity\User {
                        if ($username === 'admin') {
                            return new \App\Domain\Entity\User('1', 'admin', $this->fixedHash);
                        }
                        return null;
                    }
                    public function getAllUsers(): array {
                        return [];
                    }
                };
            },
            \App\Application\UseCase\Auth\GenerateTokenServiceInterface::class => function () {
                return new class implements \App\Application\UseCase\Auth\GenerateTokenServiceInterface {
                    public function generateToken(array $claims): string {
                        return 'dummy-token';
                    }
                };
            },

        ]);
        $container = $containerBuilder->build();

        AppFactory::setContainer($container);
        $this->app = AppFactory::create();

        $this->app->addBodyParsingMiddleware();

    }
}