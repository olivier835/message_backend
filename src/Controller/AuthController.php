<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/apip/auth/info', name: 'app_auth', methods: ['GET'])]
    public function getAuthInfo(): JsonResponse
    {
        $user = $this->security->getUser();

        if(!$user) {
            return new JsonResponse(['error' => 'No authenticated user found'], 401);
        }
        return new JsonResponse(
            ['user' => $user->getUsername(),
            'roles' => $user->getRoles(),
                'id' => $user->getId(),
            ]);
    }
}
