<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;

class DummyUserRepository implements UserRepositoryInterface
{
    public function findByUsername(string $username): ?User {
        if ($username === 'admin') {
            // Usa el hash precomputado para "secret"
            $fixedHash = '$2y$10$Wjht8DQ3F3Y.z3XgGJX1.eKw1Kz91LZZySXS6lpqU/EX9Mlu3Y1lm';
            return new User('1', 'admin', $fixedHash);
        }
        return null;
    }

    public function getAllUsers(): array {
        $fixedHash = '$2y$10$Wjht8DQ3F3Y.z3XgGJX1.eKw1Kz91LZZySXS6lpqU/EX9Mlu3Y1lm';
        return [
            new User('1', 'admin', $fixedHash),
            new User('2', 'user1', $fixedHash)
        ];
    }
}