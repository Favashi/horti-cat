<?php
// config/dependencies.php

use DI\ContainerBuilder;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Persistence\DummyUserRepository;
use App\Application\UseCase\Auth\GenerateTokenServiceInterface;
use App\Infrastructure\Auth\FirebaseJwtTokenService;

return function (ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        UserRepositoryInterface::class => \DI\create(DummyUserRepository::class),
        GenerateTokenServiceInterface::class => \DI\create(FirebaseJwtTokenService::class),
    ]);
};