<?php
// Application/UseCase/Auth/GenerateTokenServiceInterface.php
namespace App\Application\UseCase\Auth;

interface GenerateTokenServiceInterface {
    public function generateToken(array $claims): string;
}
