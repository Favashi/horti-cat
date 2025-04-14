<?php declare(strict_types=1);

namespace App\Interfaces\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Repository\UserRepositoryInterface;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/users",
    summary: "Obtener usuarios",
    tags: ["Usuarios"],
    security: [["bearerAuth" => []]],
    responses: [
        new OA\Response(
            response: "200",
            description: "Lista de usuarios",
            content: new OA\JsonContent(
                type: "array",
                items: new OA\Items(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "string", example: "1"),
                        new OA\Property(property: "username", type: "string", example: "admin")
                    ]
                )
            )
        ),
        new OA\Response(response: "401", description: "No autorizado")
    ]
)]
class UserController
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function getUsers(Request $request, Response $response, array $args): Response {
        $users = $this->userRepository->getAllUsers();
        // Para simplificar, convertir objetos a arrays (si tus entidades no tienen método toArray(), usa manualmente)
        $data = array_map(fn($user) => [
            'id' => $user->id,
            'username' => $user->username
        ], $users);
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
