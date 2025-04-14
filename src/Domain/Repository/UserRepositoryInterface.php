<?php
// Domain/Repository/UserRepositoryInterface.php
namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepositoryInterface {
    public function findByUsername(string $username): ?User;
}