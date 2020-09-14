<?php

namespace App\Controller\Login;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Login extends AbstractController
{
    /**
     * @Route(
     *     path="/login",
     *     name="app-login",
     *     methods={"POST"}
     *     )
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return Response
     */
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if (
            empty($request->get('nickname'))
            || empty($request->get('password'))
        ) {
            return new JsonResponse([
                'message' => 'Invalid credentials',
            ], 400);
        }
        if ($this->getUser()) {
            return new JsonResponse([
                'message' => 'Log in successfully',
            ]);
        }

        return new JsonResponse([
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route(
     *     "/logout",
     *     name="logout"
     * )
     */
    public function logout()
    {
//        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
