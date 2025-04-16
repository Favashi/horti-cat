<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;

class DummyUserRepository implements UserRepositoryInterface
{
    private $fixedHash = '$2y$10$oZJ3mXKZOZsxWQBM7iiKHetS.XzBDH9QRaSg8mC.ZntSpCc0vDtoa';
    public function findByUsername(string $username): ?User {
        if ($username === 'admin') {
            return new User('1', 'admin', $this->fixedHash);
        }
        return null;
    }

    public function getAllUsers(): array {
       return [
            new User('1', 'admin', $this->fixedHash),
            new User('2', 'user1', $this->fixedHash)
        ];
    }
}