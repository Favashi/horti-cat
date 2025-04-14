<?php declare(strict_types=1);

namespace App\Domain;

use App\Domain\Entity\User;

interface UserRepositoryInterface {
    public function findByUsername(string $username): ?User;
    public function getAllUsers(): array;
}