<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Plant;
use App\Entity\Garden;
use App\Form\GardenType;
use App\Entity\Flowerbed;
use App\Entity\GardenUser;
use App\Entity\GroundType;
use App\Entity\GroundAcidity;
use App\Entity\FlowerbedPlant;
use App\Entity\GardenFlowerbed;
use App\Repository\GardenUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\GardenFlowerbedRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GardenController extends AbstractController
{
    #[Route('/garden', name: 'garden')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $user =  $this->getUser();
        $user_gardens = $doctrine->getRepository(GardenUser::class)->findBy(array('user' => $user));

        $garden_ids = array();
        foreach ($user_gardens as $garden) {
            array_push($garden_ids, $garden->getGarden()->getId());
        }
        $gardens = $doctrine->getRepository(Garden::class)->findBy(array('id' => $garden_ids));

        $message = $request->query->get('message') ? $request->query->get('message') : null;

        return $this->render('garden/index.html.twig', [
            'gardens' => $gardens,
            'message' => $message
        ]);
    }

    #[Route('/garden/new', name: 'create_garden')]
    public function create(Request $request, ManagerRegistry $doctrine, UrlGeneratorInterface $urlGenerator): Response
    {
        $garden = new Garden();
        $form = $this->createForm(GardenType::class, $garden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //mettre ici les setter des données non remplies
            $garden->setDateAdd(new \DateTime());
            $garden->setDateUpd(new \DateTime());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($garden);
            $entityManager->flush();
            $message = 'Le jardin a bien été enregistré';

            $garden_user = new GardenUser();
            $garden_user->setUSer($this->getUser());
            $garden_user->setGarden($garden);
            $garden_user->setIsOwner(true);
            
            $garden_repo = new GardenUserRepository($doctrine);
            $garden->addGardenUser($garden_user);
            $garden_repo->save($garden_user, true);
      
            $secondRouteUrl = $urlGenerator->generate('garden', ['message' => $message]);

            // Créer une réponse de redirection vers la deuxième route
            $response = new RedirectResponse($secondRouteUrl);
        
            // Retourner la réponse
            return $response;

            /*$gardens = $doctrine->getRepository(Garden::class)->findAll();
            return $this->render('garden/index.html.twig', [
                'gardens' => $gardens,
                'message' => $message,
                //'user' => $user,
            ]);*/

        } else {
            return $this->render('garden/create.html.twig', [
                'gardenForm' => $form->createView(),
            ]);
        }
    }

    #[Route('/garden/edit/{id}', name: 'edit_garden')]
    public function edit(Garden $garden, Request $request, ManagerRegistry $doctrine) {

        $form = $this->createForm(GardenType::class, $garden);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            //Dans l'edit pas besoin du persist
            $entityManager->flush();
            return new Response('Le jardin a bien été mis à jour');
            } else {
                return $this->render('garden/edit.html.twig', [
                    'gardenForm' => $form->createView(),
                    'garden' => $garden,
                ]);
            }
    }

    #[Route('/garden/remove/{id}', name: 'remove_garden')]
    public function remove(Garden $garden, Request $request, ManagerRegistry $doctrine): Response {

        //on récupère les ids des parterres pour les supprimer par la suite
        $existingGardenUsers = $doctrine->getRepository(GardenUser::class)->findBy(array('garden' => $garden));
        
        //on supprime tous les enregistrements des parterres de ce jardin dans la table intermediaire
        foreach($existingGardenUsers as $existingGardenUser) {

            $entityManager = $doctrine->getManager();
            $entityManager->remove($existingGardenUser);
            $entityManager->flush();
            
        }
        
        $entityManager = $doctrine->getManager();
        $entityManager->remove($garden);
        $entityManager->flush();
        return $this->redirectToRoute('garden');
    }

    #[Route('/garden/maintenance/{id}', name: 'maintenance_garden')]
    #[IsGranted('ROLE_USER')]
    public function maintenance(Garden $garden, ManagerRegistry $doctrine): Response
    {
        //on récupère tous les id parterres correspondant au jardin
        $gardenFlowerbeds = $doctrine->getRepository(GardenFlowerbed::class)->findBy(array('garden' => $garden));

        $flowerbed_ids = [];
        

        foreach($gardenFlowerbeds as $gardenFlowerbed) {
            array_push($flowerbed_ids, $gardenFlowerbed->getFlowerbed()->getId());
        }



        //à l'aide de leur id on récupère leur données
        $flowerbeds = $doctrine->getRepository(Flowerbed::class)->findBy(array('id' => $flowerbed_ids));

        //autre technique por sortir directement un tableua exploitable = plus maintenable
        /*$flowerbedsQuery = $doctrine->getRepository(Flowerbed::class)->createQueryBuilder('f')
            ->where('f.id IN (:ids)')
            ->setParameter('ids', $flowerbed_ids)
            ->getQuery();

        $flowerbeds = $flowerbedsQuery->getArrayResult();*/


        //on met ces données en forme dans un tableau associatif qu'on enverra ensuite dans le template
        $flowerbeds_data = [];
        $flowerbed_data = [];

        
        foreach($flowerbeds as $flowerbed) {
            
            $flowerbed_data['formtype'] = $flowerbed->getFormtype();
            $flowerbed_data['kind'] = $flowerbed->getKind();
            $flowerbed_data['top'] = $flowerbed->getTopy();
            $flowerbed_data['left'] = $flowerbed->getLeftx();
            $flowerbed_data['width'] = $flowerbed->getWidth();
            $flowerbed_data['height'] = $flowerbed->getHeight();
            $flowerbed_data['ray'] = $flowerbed->getRay();
            $flowerbed_data['scalex'] = $flowerbed->getScalex();
            $flowerbed_data['scaley'] = $flowerbed->getScaley();
            $flowerbed_data['fill'] = $flowerbed->getFill();
            $flowerbed_data['fillOpacity'] = $flowerbed->getFillOpacity();
            $flowerbed_data['stroke'] = $flowerbed->getStroke();
            $flowerbed_data['flipangle'] = $flowerbed->getFlipangle();
            $flowerbed_data['shadowtype'] = $flowerbed->getShadowtype();
            $flowerbed_data['isGardenLimit'] = (int)$flowerbed->isGardenLimit();
            
            $flowerbed_data['groundtype'] = $flowerbed->getGroundType() ? $flowerbed->getGroundType()->getId() : null;
            $flowerbed_data['groundacidity'] = $flowerbed->getGroundAcidity() ? $flowerbed->getGroundAcidity()->getId() : null;

        
            if ($flowerbed->getKind() == "plant") {
                $plant_data = [];
                
                //on récupère la plante relié au parterre
                $FlowerbedsPlant = $doctrine->getRepository(FlowerbedPlant::class)->findBy(array('flowerbed' => $flowerbed));
                //et les infos de cette plante
                //$plant = $doctrine->getRepository(Plant::class)->findBy(array('id' => $FlowerbedsPlant[0]->getPlant()->getId()));

                $plantQuery = $doctrine->getRepository(Plant::class)->createQueryBuilder('p')
                    ->where('p.id = :plantId')
                    ->setParameter('plantId', $FlowerbedsPlant[0]->getPlant()->getId())
                    ->getQuery();

                $plantArray = $plantQuery->getArrayResult();

                $plantotherDataQuery = $doctrine->getRepository(Plant::class)->createQueryBuilder('p')
                    ->select('p, category, color, usefulnesses')
                    ->leftJoin('p.category', 'category')
                    ->leftJoin('p.color', 'color') 
                    ->leftJoin('p.usefulnesses', 'usefulnesses')
                    ->where('p.id = :plantId')
                    ->setParameter('plantId', $FlowerbedsPlant[0]->getPlant()->getId())
                    ->getQuery();

                $OtherDataArray = $plantotherDataQuery->getArrayResult();


                //on les range dans un tableau
                $plant_data['planting_date'] = $FlowerbedsPlant[0]->getPlantingDate();
                $plant_data['plant'] = $plantArray[0];

                $otherData = $OtherDataArray[0]; // Obtenir les données additionnelles de la première ligne
                $plant_data['plant']['category'] = $otherData['category'];
                $plant_data['plant']['color'] = $otherData['color'];
                $plant_data['plant']['usefulnesses'] = $otherData['usefulnesses'];
                
                //on les ajoutes aux données envoyés au parterre
                $flowerbed_data['plant'] = $plant_data;
            }
             


            array_push($flowerbeds_data, $flowerbed_data);
        }

        return $this->render('garden/maintenance.html.twig', [
            'garden' => $garden,
            'flowerbeds' => $flowerbeds_data
        ]);
    }

    #[Route('/garden/save/{id}', name: 'save_garden')]
    #[IsGranted('ROLE_USER')]
    public function save(Garden $garden, ManagerRegistry $doctrine): Response
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérer le contenu JSON de la requête
            $jsonData = file_get_contents('php://input');

            $data = json_decode($jsonData, true);
            $entityManager = $doctrine->getManager();

            //suppression des parterres déjà existant et de leu relation avec le jardin dans la table intermédiare
            //on récupère tous les parterres qui sont reliés au jardin dans la table intermédiaire
            $existingGardenFlowerbeds = $doctrine->getRepository(GardenFlowerbed::class)->findBy(array('garden' => $garden));
            //on récupère les ids des parterres pour les supprimer par la suite
            $flowerbed_ids = [];

            //on supprime tous les enregistrements des parterres de ce jardin dans la table intermediaire
            foreach($existingGardenFlowerbeds as $existingGardenFlowerbed) {
                array_push($flowerbed_ids, $existingGardenFlowerbed->getFlowerbed()->getId());

                $entityManager->remove($existingGardenFlowerbed);
                
                
            }
            
            //on récupère les parterres grâce à leurs ids
            $existingFlowerbeds = $doctrine->getRepository(Flowerbed::class)->findBy(array('id' => $flowerbed_ids));

            //on récupère les plantes des parterres grâce à leurs ids
            $existingFlowerbedsPlants = $doctrine->getRepository(FlowerbedPlant::class)->findBy(array('flowerbed' => $existingFlowerbeds));

            //on supprime les plantes des parterres de la table GardenPlant
            foreach($existingFlowerbedsPlants as $existingFlowerbedsPlant) {
                
                $entityManager->remove($existingFlowerbedsPlant);
                
            }

            //on supprime les parterres de la table Parterre
            foreach($existingFlowerbeds as $existingFlowerbed) {
                
                $entityManager->remove($existingFlowerbed);
                
            }

            $entityManager->flush();

            
                if ($data) {
                    foreach($data as $obj) {
                        $flowerbed = new Flowerbed();
                        //mettre ici les setter des données non remplies
                        $flowerbed->setTitle("test"); //TO DO setter title au clique sur un parterre en front
                        $flowerbed->setDateUpd(new DateTime());
                        $flowerbed->setFormtype($obj['formtype']);
                        $flowerbed->setKind($obj['kind']);
                        $flowerbed->setTopy((float)$obj['top']);
                        $flowerbed->setLeftx((float)$obj['left']);
                        $flowerbed->setWidth((float)$obj['width']);
                        $flowerbed->setHeight((float)$obj['height']);
                        $flowerbed->setRay((float)$obj['ray']);
                        $flowerbed->setScalex($obj['scalex']);
                        $flowerbed->setScaley($obj['scaley']);
                        $flowerbed->setFill($obj['fill']);
                        $flowerbed->setFillOpacity($obj['opacity']);
                        $flowerbed->setStroke($obj['stroke']);
                        $flowerbed->setFlipangle((float)$obj['flipangle']);
                        $flowerbed->setShadowtype($obj['shadowtype']);
                        $flowerbed->setGardenLimit($obj['isGardenLimit']);
                        
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
                        
                        $garden_flowerbed_repo = new GardenFlowerbedRepository($doctrine);
                        $flowerbed->addGardenFlowerbed($garden_flowerbed);
                        $garden_flowerbed_repo->save($garden_flowerbed, true);

                        

                        if ($obj['plant']) {
                            //TODO quand un shape est supprimé et que c'est une plante, supprimer aussi son occurence dans la table garden_plant
                            $lastFlowerbed = $entityManager->getRepository(Flowerbed::class)->findOneBy([], ['id' => 'DESC']);

                            if ($lastFlowerbed) {
                                $lastFlowerbedId = $lastFlowerbed->getId();
                                $plantFlowerbed = $entityManager->getRepository(Flowerbed::class)->find($lastFlowerbedId);
                            }
                            
                            //enregistrement de la plante si la forme est une plante
                            $plant = $entityManager->getRepository(Plant::class)->find((int)$obj['plant']['id']);

                            $garden_plant = new FlowerbedPlant();
                            //mettre ici les setter des données non remplies
                            $garden_plant->setGarden($garden); //TO DO setter title au clique sur un parterre en front
                            $garden_plant->setPlant($plant);
                            $garden_plant->setFlowerbed($plantFlowerbed);

                            $today = new \DateTime(); 
                            $garden_plant->setPlantingDate($today);
        
                            $entityManager = $doctrine->getManager();
                            $entityManager->persist($garden_plant);
                            $entityManager->flush();
                        }
                        
                        
                        
                    }
                }
                
        } else {
            $message = "la méthode passée n'est pas en POST";
        }
        $message = 'Le jardin et ses parterres ont bien été sauvegardés !';
        $response = new Response($message);

        return $response;
    }



}
