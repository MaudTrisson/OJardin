<?php

namespace App\Controller;

use DateTime;
use App\Entity\Garden;
use App\Entity\FlowerbedPlant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\FlowerbedPlantMaintenanceAction;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\FlowerbedPlantMaintenanceActionRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MaintenanceActionController extends AbstractController
{
    #[Route('/maintenanceaction/done', name: 'maintenance_action_done')]
    public function done(Request $request, ManagerRegistry $doctrine, UrlGeneratorInterface $urlGenerator): Response
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérer le contenu JSON de la requête
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            $flowerbedPlant = $doctrine->getRepository(FlowerbedPlant::class)->findBy(array('id' => $data['flowerbedPlantId']));
            $flowerbedPlantMaintenanceAction = $doctrine->getRepository(FlowerbedPlantMaintenanceAction::class)->findBy(array('flowerbedPlant' => $flowerbedPlant));
            $flowerbedPlantMaintenanceAction[0]->setAchievementDate(new DateTime());
            
            $flowerbed_plant_maintenance_action_repo = new FlowerbedPlantMaintenanceActionRepository($doctrine);
            $flowerbed_plant_maintenance_action_repo->save($flowerbedPlantMaintenanceAction[0], true);
            
            
        }

        $message = 'action réalisée enregistrée !';

        $response = new Response($message);

        return $response;
        
    }
    
}
