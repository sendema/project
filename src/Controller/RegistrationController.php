<?php

namespace App\Controller;

use App\Service\RegistrationService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RegistrationController
{
    public function __construct(
        private readonly RegistrationService $registrationService
    ) {}

    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        try {
            $user = $this->registrationService->register(
                $data['email'] ?? '',
                $data['password'] ?? ''
            );

            $response->getBody()->write(json_encode([
                'id' => $user->getId(),
                'email' => $user->getEmail()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    }
}