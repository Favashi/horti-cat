<?php declare(strict_types=1);

// src/Interfaces/Controllers/AuthController.php

namespace App\Interfaces\Controllers;

use App\Application\UseCase\Auth\AuthenticateUser;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/auth/login",
    summary: "Autenticación de usuario",
    tags: ["Autenticación"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["username", "password"],
            properties: [
                new OA\Property(property: "username", type: "string", example: "admin"),
                new OA\Property(property: "password", type: "string", example: "secret")
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: "200",
            description: "Token JWT",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "token", type: "string", example: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
                ]
            )
        ),
        new OA\Response(response: "401", description: "Credenciales inválidas")
    ],
    servers: [ new OA\Server(url: "/") ]
)]
class AuthController
{
    public function __construct(private AuthenticateUser $authenticateUser) {}

    public function login(Request $request, Response $response): Response {
        $params = (array)$request->getParsedBody();
        try {
            $token = $this->authenticateUser->execute($params['username'], $params['password']);
            $response->getBody()->write(json_encode(['token' => $token]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    }
}