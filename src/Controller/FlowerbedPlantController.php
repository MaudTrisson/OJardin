<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Entity\FlowerbedPlant;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\FlowerbedPlantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FlowerbedPlantController extends AbstractController
{
    #[Route('/flowerbed/plant', name: 'app_flowerbed_plant')]
    public function index(): Response
    {
        return $this->render('flowerbed_plant/index.html.twig', [
            'controller_name' => 'FlowerbedPlantController',
        ]);
    }

}
