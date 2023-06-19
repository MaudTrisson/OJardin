<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Entity\Flowerbed;
use App\Entity\GardenFlowerbed;
use App\Repository\FlowerbedRepository;
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

    #[Route('/flowerbed/save/{id}', name: 'save_flowerbed')]
    #[IsGranted('ROLE_USER')]
    public function save(Garden $garden, ManagerRegistry $doctrine): Response
    {
        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérer le contenu JSON de la requête
            $jsonData = file_get_contents('php://input');
            
            //decode une première fois
            $data = json_decode($jsonData, true);

            if ($data == "[]") {

                $message = "   il n'y a rien à sauvegarder !";
                
            } else {
            
                //decode une deuxième fois car le premier format de donnée comportait des défaut qui a étét réglé avec le premier decode
                $array_data = json_decode($data);

                //suppression des parterres déjà existant et de leu relation avec le jardin dans la table intermédiare

                //on récupère tous les parterres qui sont reliés au jardin dans la table intermédiaire
                $existingGardenFlowerbeds = $doctrine->getRepository(GardenFlowerbed::class)->findBy(array('garden' => $garden));
                //on récupère les ids des parterres pour les supprimer par la suite
                $flowerbed_ids = [];

                //on supprime tous les enregistrements des parterres de ce jardin dans la table intermediaire
                foreach($existingGardenFlowerbeds as $existingGardenFlowerbed) {
                    array_push($flowerbed_ids, $existingGardenFlowerbed->getFlowerbed()->getId());

                    $entityManager = $doctrine->getManager();
                    $entityManager->remove($existingGardenFlowerbed);
                    $entityManager->flush();
                    
                }

                //on récupère les parterres grâce à leurs ids
                $existingFlowerbeds = $doctrine->getRepository(Flowerbed::class)->findBy(array('id' => $flowerbed_ids));
                //on supprime les parterres de la table Parterre
                foreach($existingFlowerbeds as $existingFlowerbed) {
                    
                    $entityManager = $doctrine->getManager();
                    $entityManager->remove($existingFlowerbed);
                    $entityManager->flush();
                    
                }


                
            
                foreach($array_data as $obj) {
                    $message = "   votre jardin et ces parterres ont bien été sauvegardés !";
                    $flowerbed = new Flowerbed();
                    
                    //mettre ici les setter des données non remplies
                    $flowerbed->setTitle("test");
                    $flowerbed->setDateUpd(new \DateTime());
                    $flowerbed->setFormtype($obj->type);
                    $flowerbed->setTopy((float)$obj->top);
                    $flowerbed->setLeftx((float)$obj->left);
                    $flowerbed->setWidth((float)$obj->width);
                    $flowerbed->setHeight((float)$obj->height);
                    $flowerbed->setRay((float)0);
                    $flowerbed->setScalex($obj->scaleX);
                    $flowerbed->setScaley($obj->scaleY);
                    $flowerbed->setFill($obj->fill);
                    $flowerbed->setStroke($obj->stroke);
                    $flowerbed->setFlipangle((float)$obj->angle);
                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($flowerbed);
                    $entityManager->flush();
                    
                    $message = 'Le jardin et ses parterres ont bien été sauvegardés !';

                    $garden_flowerbed = new GardenFlowerbed();
                    $garden_flowerbed->setFlowerbed($flowerbed);
                    $garden_flowerbed->setGarden($garden);
                    $garden_flowerbed->setFlowerbedLevel(1);
                    
                    $flowerbed_repo = new GardenFlowerbedRepository($doctrine);
                    $flowerbed->addGardenFlowerbed($garden_flowerbed);
                    $flowerbed_repo->save($garden_flowerbed, true);
        
                }
            
            }
   
        } else {
            $message = "   la méthode passée n'est pas en POST";
        }

        $response = new Response($message);

        return $response;
    }
}
