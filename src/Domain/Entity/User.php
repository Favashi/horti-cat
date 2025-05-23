<?php
// Domain/Entity/User.php
namespace App\Domain\Entity;

class User {
    public function __construct(
        public readonly string $id,
        public readonly string $username,
        public readonly string $hashedPassword
    ) {}
}
