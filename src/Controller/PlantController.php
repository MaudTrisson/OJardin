<?php

namespace App\Controller;

use App\Entity\Color;
use App\Entity\Plant;
use App\Entity\Garden;
use App\Form\PlantType;
use App\Entity\Category;
use App\Entity\GroundType;
use App\Entity\ShadowType;
use App\Entity\Usefulness;
use App\Entity\GroundAcidity;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PlantController extends AbstractController
{
    #[Route('/plant', name: 'plant')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $plants = $doctrine->getRepository(Plant::class)->findAll();
        return $this->render('plant/index.html.twig', [
            'plants' => $plants,
        ]);
    }

    #[Route('/plant/new', name: 'create_plant')]
    public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $plant = new Plant();
        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            if ($image) {
                //récupère le nom sans l'extension
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                //au cas où on utilise un jour les images dans l'url, autant les slugifier :
                $safeFilename = $slugger->slug($originalFilename);
                //uniqid nous renvoie un id aléatoire  basé sur un timestamp
                //guessExtension permet de récupérer l'extension
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                
                //on test de télécharger l'image sur le serveur
                try {
                    $image->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                        );
                        $plant->setImage($newFilename);
                    } catch (FileException $e) {
                    // Si ça se passe mal, c'est ici qu'on redirige avec un message
                }
            } 
            $entityManager = $doctrine->getManager();
            $entityManager->persist($plant);
            $entityManager->flush();

            $message = "Votre plante a bien été ajoutée.";
            $plants = $doctrine->getRepository(Plant::class)->findAll();
            return $this->render('plant/index.html.twig', [
                'plants' => $plants,
                'message' => $message
            ]);

        } else {
            return $this->render('plant/create.html.twig', [
                'plantForm' => $form->createView(),
            ]);
        }
    }

    #[Route('/plant/edit/{id}', name: 'edit_plant')]
    public function edit(Plant $plant, Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger) {

        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            if ($image) {
                //récupère le nom sans l'extension
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                //au cas où on utilise un jour les images dans l'url, autant les slugifier :
                $safeFilename = $slugger->slug($originalFilename);
                //uniqid nous renvoie un id aléatoire  basé sur un timestamp
                //guessExtension permet de récupérer l'extension
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                
                //on test de télécharger l'image sur le serveur
                try {
                    $image->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                        );
                        $plant->setImage($newFilename);
                    } catch (FileException $e) {
                    // Si ça se passe mal, c'est ici qu'on redirige avec un message
                }
            } 

            
            $entityManager = $doctrine->getManager();
            //Dans l'edit pas besoin du persist
            $entityManager->flush();
            return new Response('La plante a bien été mis à jour');
            } else {
                return $this->render('plant/edit.html.twig', [
                    'plantForm' => $form->createView(),
                    'plant' => $plant,
                ]);
                
            }
    }

    #[Route('/plant/remove/{id}', name: 'remove_plant')]
    public function remove(Plant $plant, Request $request, ManagerRegistry $doctrine): Response {
        
        $entityManager = $doctrine->getManager();
        $entityManager->remove($plant);
        $entityManager->flush();
        return $this->redirectToRoute('plant');
    }

    #[Route('/plant/search', name: 'search_plant')]
    public function search(Request $request, ManagerRegistry $doctrine): Response {
        
        $datas = json_decode($request->getContent(), true);

        $entityManager = $doctrine->getManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder
        ->select('p, gt, ga, cat, u, col')
        ->from(Plant::class, 'p')
        ->join('p.ground_types', 'gt')
        ->join('p.ground_acidities', 'ga')
        ->join('p.category', 'cat')
        ->join('p.usefulnesses', 'u')
        ->join('p.color', 'col');

        if (isset($datas['groundType']) && intval($datas['groundType'])) {
            $queryBuilder->andwhere('gt.id = :groundtypeId');
            $queryBuilder->setParameter('groundtypeId', intval($datas['groundType']));
        }

        if (isset($datas['groundAcidity']) && intval($datas['groundAcidity'])) {
            $queryBuilder->andWhere('ga.id = :groundacidityId');
            $queryBuilder->setParameter('groundacidityId', intval($datas['groundAcidity']));
        }

        if (isset($datas['shadowtype']) && intval($datas['shadowtype'])) {
            $queryBuilder->andWhere('p.shadowtype = :shadowtypeId');
            $queryBuilder->setParameter('shadowtypeId', intval($datas['shadowtype']));
        }

        if (isset($datas['category']) && intval($datas['category'])) {
            $queryBuilder->andWhere('cat.id = :categoryId');
            $queryBuilder->setParameter('categoryId', intval($datas['category']));
        }

        if (isset($datas['usefulness']) && intval($datas['usefulness'])) {
            $queryBuilder->andWhere('u.id = :usefulnessId');
            $queryBuilder->setParameter('usefulnessId', intval($datas['usefulness']));
        }

        if (isset($datas['color']) && intval($datas['color'])) {
            $queryBuilder->andWhere('p.color = :colorId');
            $queryBuilder->setParameter('colorId', intval($datas['color']));
        }

        if (isset($datas['name']) && $datas['name']) {
            $queryBuilder->andWhere('p.name LIKE :name');
            $queryBuilder->setParameter('name', '%' . $datas['name'] . '%');
        }

        

        $searchPlants = $queryBuilder->getQuery()->getArrayResult();

        if (!$searchPlants) {
            return new JsonResponse('pas de données');
        } else {
     
            //$plantsArray = array();
            /*foreach ($searchPlants as $plant) {
                dd($plant);
                $plantArray = array(
                    'id' => $plant->getId(),
                    'name' => $plant->getName(),
                    'description' => $plant->getDescription(),
                    'image' => $plant->getImage(),
                    'lifetime' => $plant->getLifetime(),
                    'height' => $plant->getHeight(),
                    'width' => $plant->getWidth(),
                    'shadowtype' => intval($datas['shadowtype']),
                    'groundtype' => intval($datas['groundType']),
                    'groundacidity' => intval($datas['groundAcidity']),
                    /*'category' =>,
                    'usefulnesses' =>,
                    'color' =>*/
                    // Ajoutez d'autres propriétés de Plant que vous souhaitez inclure dans le tableau
                /*);
                $plantsArray[] = $plantArray;
            }*/
            return new JsonResponse($searchPlants);
        } 
    }

    //permet de récupérer les propriétés d'un parterre afin de les afficher en front
    #[Route('/plant/properties', name: 'plant_properties')]
    public function getProperties(ManagerRegistry $doctrine): Response
    {
        //TODO mettre toutes les propriétés de plante
        $properties = [];
        $properties['shadowtypes'] = [];
        $properties['groundtypes'] = [];
        $properties['groundacidities'] = [];
        $properties['categories'] = [];
        $properties['usefulnesses'] = [];
        $properties['colors'] = [];
        $properties['width'] = [];
        $properties['rainfall_need'] = [];
        $properties['sunshine_need'] = [];
        $properties['freeze_sensibility'] = [];
        $properties['lifetime'] = [];
        $properties['planting_date'] = [];
        $properties['flowwering_period'] = [];
        $properties['leaves_persistence'] = [];


        $shadowTypes = $doctrine->getRepository(ShadowType::class)->findAll();
        $groundTypes = $doctrine->getRepository(GroundType::class)->findAll();
        $groundAcidities = $doctrine->getRepository(GroundAcidity::class)->findAll();
        $categories = $doctrine->getRepository(Category::class)->findAll();
        $usefulnesses = $doctrine->getRepository(Usefulness::class)->findAll();
        $colors = $doctrine->getRepository(Color::class)->findAll();

        foreach($shadowTypes as $shadowType) {
            array_push($properties['shadowtypes'], ["id" => $shadowType->getId(), "name" => $shadowType->getName(), "color" => $shadowType->getColor(), "color_opacity" => $shadowType->getColorOpacity()]);
        }

        foreach($groundTypes as $groundType) {
            array_push($properties['groundtypes'], ["id" => $groundType->getId(), "name" => $groundType->getName(), "image" => $groundType->getImage()]);
        }

        foreach($groundAcidities as $groundAcidity) {
            array_push($properties['groundacidities'], ["id" => $groundAcidity->getId(), "name" => $groundAcidity->getName()]);
        }

        foreach($categories as $categorie) {
            array_push($properties['categories'], ["id" => $categorie->getId(), "name" => $categorie->getName()]);
        }

        foreach($usefulnesses as $usefulness) {
            array_push($properties['usefulnesses'], ["id" => $usefulness->getId(), "name" => $usefulness->getName()]);
        }

        foreach($colors as $color) {
            array_push($properties['colors'], ["id" => $color->getId(), "name" => $color->getName(), "hexa_code" => $color->getHexaCode()]);
        }


        
        $json_properties = json_encode($properties);

        $response = new Response($json_properties);

        return $response;
    }
}
