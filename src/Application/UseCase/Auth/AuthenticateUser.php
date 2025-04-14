<?php
// Application/UseCase/Auth/AuthenticateUser.php
namespace App\Application\UseCase\Auth;

use App\Domain\Repository\UserRepositoryInterface;
use App\Application\UseCase\Auth\GenerateTokenServiceInterface;

class AuthenticateUser {
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private GenerateTokenServiceInterface $tokenService
    ) {}

    public function execute(string $username, string $password): string {
        $user = $this->userRepo->findByUsername($username);
        if (!$user || !password_verify($password, $user->hashedPassword)) {
            throw new \Exception('Credenciales inválidas');
        }

        return $this->tokenService->generateToken([
            'sub' => $user->id,
            'username' => $user->username
        ]);
    }
}