<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener extends AuthenticationSuccessHandler
{
    public function handleAuthenticationSuccess(UserInterface $user, $jwt = null)
    {
        $response = parent::handleAuthenticationSuccess($user, $jwt);

        $newData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'token'=>  json_decode($response->getContent(),true)['token'],
        ];
        $response->setData($newData);

        return new JsonResponse([
            'status'=> $response->getStatusCode(),
            'message'=> 'LOGIN SUCCESS',
            'result'=> json_decode($response->getContent(),true)
        ]);
    }
}
