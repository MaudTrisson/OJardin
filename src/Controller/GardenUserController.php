<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GardenUserController extends AbstractController
{
    #[Route('/garden/user', name: 'app_garden_user')]
    public function index(): Response
    {
        return $this->render('garden_user/index.html.twig', [
            'controller_name' => 'GardenUserController',
        ]);
    }
}
