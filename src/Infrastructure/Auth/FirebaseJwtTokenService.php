<?php
// Infrastructure/Auth/FirebaseJwtTokenService.php
namespace App\Infrastructure\Auth;

use App\Application\UseCase\Auth\GenerateTokenServiceInterface;
use Firebase\JWT\JWT;

class FirebaseJwtTokenService implements GenerateTokenServiceInterface {
    public function generateToken(array $claims): string {
        $claims['iat'] = time();
        $claims['exp'] = time() + 3600;

        return JWT::encode($claims, $_ENV['JWT_SECRET'], 'HS256');
    }
}