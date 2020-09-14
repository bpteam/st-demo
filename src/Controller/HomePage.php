<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePage extends AbstractController
{
    /**
     * @Route(
     *     path="/",
     *     name="app-home"
     *     )
     * @return Response
     */
    public function index(): Response
    {
        return new JsonResponse(['hello' => 'world']);
    }
}