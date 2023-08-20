<?php

namespace App\Controller;

use App\Entity\Plant;
use App\Entity\Garden;
use App\Entity\Flowerbed;
use App\Entity\GroundType;
use App\Entity\ShadowType;
use App\Entity\GroundAcidity;
use App\Entity\GardenFlowerbed;
use App\Repository\FlowerbedRepository;
use App\Repository\ShadowTypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\GardenFlowerbedRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FlowerbedController extends AbstractController
{
    /*#[Route('/flowerbed', name: 'app_flowerbed')]
    public function index(): Response
    {
        $flowerbed = new Flowerbed();
        $flowerbed->setTitle("hey");
        return $this->render('flowerbed/index.html.twig', [
            'flowerbed' => $flowerbed,
            'controller_name' => 'FlowerbedController',
        ]);
    }*/

    

    //permet de récupérer les propriétés d'un parterre afin de les afficher en front
    #[Route('/flowerbed/properties', name: 'app_flowerbed_properties')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $properties = [];
        $properties['shadowtypes'] = [];
        $properties['groundtypes'] = [];
        $properties['groundacidities'] = [];

        $shadowTypes = $doctrine->getRepository(ShadowType::class)->findAll();
        $groundTypes = $doctrine->getRepository(GroundType::class)->findAll();
        $groundAcidities = $doctrine->getRepository(GroundAcidity::class)->findAll();

        foreach($shadowTypes as $shadowType) {
            array_push($properties['shadowtypes'], ["id" => $shadowType->getId(), "name" => $shadowType->getName(), "color" => $shadowType->getColor(), "color_opacity" => $shadowType->getColorOpacity()]);
        }

        foreach($groundTypes as $groundType) {
            array_push($properties['groundtypes'], ["id" => $groundType->getId(), "name" => $groundType->getName(), "image" => $groundType->getImage()]);
        }

        foreach($groundAcidities as $groundAcidity) {
            array_push($properties['groundacidities'], ["id" => $groundAcidity->getId(), "name" => $groundAcidity->getName()]);
        }
        
        $json_properties = json_encode($properties);

        $response = new Response($json_properties);

        return $response;
    }
}
