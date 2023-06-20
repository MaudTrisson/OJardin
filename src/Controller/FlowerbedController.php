<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Entity\Flowerbed;
use App\Entity\GardenFlowerbed;
use App\Entity\GroundAcidity;
use App\Entity\GroundType;
use App\Entity\ShadowType;
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

    #[Route('/flowerbed/save/{id}', name: 'save_flowerbed')]
    #[IsGranted('ROLE_USER')]
    public function save(Garden $garden, ManagerRegistry $doctrine): Response
    {
        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérer le contenu JSON de la requête
            $jsonData = file_get_contents('php://input');

            $data = json_decode($jsonData, true);
            
            if ($data == "[]" || !$data) {

                $message = "";
                
            } else {

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
            
                foreach($data as $obj) {
                    $message = "   votre jardin et ces parterres ont bien été sauvegardés !";
                    $flowerbed = new Flowerbed();
                    //mettre ici les setter des données non remplies
                    $flowerbed->setTitle("test"); //TO DO setter title au clique sur un parterre en front
                    $flowerbed->setDateUpd(new \DateTime());
                    $flowerbed->setFormtype($obj['formtype']);
                    $flowerbed->setTopy((float)$obj['top']);
                    $flowerbed->setLeftx((float)$obj['left']);
                    $flowerbed->setWidth((float)$obj['width']);
                    $flowerbed->setHeight((float)$obj['height']);
                    //$flowerbed->setRay((float)$obj['ray']);
                    $flowerbed->setScalex($obj['scalex']);
                    $flowerbed->setScaley($obj['scaley']);
                    $flowerbed->setFill($obj['fill']);
                    $flowerbed->setFillOpacity($obj['opacity']);
                    $flowerbed->setStroke($obj['stroke']);
                    $flowerbed->setFlipangle((float)$obj['flipangle']);
                    $flowerbed->setShadowtype($obj['shadowtype']);
                    
                    if (isset($obj['groundtype'])) {
                        $groundTypeId = $obj['groundtype'];
                        $groundType = $doctrine->getRepository(GroundType::class)->find($groundTypeId);
                        if ($groundType !== null) {
                            $flowerbed->setGroundType($groundType);
                        }
                    }

                    if (isset($obj['groundacidity'])) {
                        $groundAcidityId = $obj['groundacidity'];
                        $groundAcidity = $doctrine->getRepository(GroundAcidity::class)->find($groundAcidityId);
                        if ($groundAcidity !== null) {
                            $flowerbed->setGroundAcidity($groundAcidity);
                        }
                    }

                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($flowerbed);
                    $entityManager->flush();

                    
                    $message = 'Le jardin et ses parterres ont bien été sauvegardés !';

                    $garden_flowerbed = new GardenFlowerbed();
                    $garden_flowerbed->setFlowerbed($flowerbed);
                    $garden_flowerbed->setGarden($garden);
                    $garden_flowerbed->setFlowerbedLevel(1);
                    
                    $garden_flowerbed_repo = new GardenFlowerbedRepository($doctrine);
                    $flowerbed->addGardenFlowerbed($garden_flowerbed);
                    $garden_flowerbed_repo->save($garden_flowerbed, true);
                    
                }
            
            }
   
        } else {
            $message = "   la méthode passée n'est pas en POST";
        }

        $response = new Response($message);

        return $response;
    }

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
