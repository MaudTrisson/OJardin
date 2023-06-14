<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Garden;
use App\Form\GardenType;
use App\Entity\GardenUser;
use App\Repository\GardenUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GardenController extends AbstractController
{
    #[Route('/garden', name: 'garden')]
    #[IsGranted('ROLE_USER')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user =  $this->getUser();
        $user_gardens = $doctrine->getRepository(GardenUser::class)->findBy(array('user' => $user));

        $garden_ids = array();
        foreach ($user_gardens as $garden) {
            array_push($garden_ids, $garden->getGarden()->getId());
        }
        $gardens = $doctrine->getRepository(Garden::class)->findBy(array('id' => $garden_ids));

        return $this->render('garden/index.html.twig', [
            'gardens' => $gardens,
        ]);
    }

    #[Route('/garden/new', name: 'create_garden')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
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
      

            $gardens = $doctrine->getRepository(Garden::class)->findAll();
            return $this->render('garden/index.html.twig', [
                'gardens' => $gardens,
                'message' => $message,
                //'user' => $user,
            ]);

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
        
        $entityManager = $doctrine->getManager();
        $entityManager->remove($garden);
        $entityManager->flush();
        return $this->redirectToRoute('garden');
    }

    #[Route('/garden/maintenance/{id}', name: 'maintenance_garden')]
    #[IsGranted('ROLE_USER')]
    public function maintenance(Garden $garden, ManagerRegistry $doctrine): Response
    {
        return $this->render('garden/maintenance.html.twig', [
            'garden' => $garden,
        ]);
    }

    //TODO : déplacer ça dans Flowerbed
    #[Route('/garden/save/{id}', name: 'save_garden')]
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
            
                foreach($array_data as $obj) {
                    $message = "   votre jardin et ces parterres ont bien été sauvegardés !";
                    /*$flowerbed = new Garden();

                    //mettre ici les setter des données non remplies
                    $flowerbed->setDateAdd($obj->type);
                    $flowerbed->setDateUpd($obj->width);
                    $flowerbed->setDateUpd($obj->height);
                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($flowerbed);
                    $entityManager->flush();
                    $message = 'Le jardin et ses parterres ont bien été sauvegardés !';

                    $garden_flowerbed = new GardenFlowerbed();
                    $garden_flowerbed->setFlowerbed($this->getUser());
                    $garden_flowerbed->setGarden($garden);
                    
                    $flowerbed_repo = new FlowerbedRepository($doctrine);
                    $flowerbed->addGardenFlowerbed($garden_flowerbed);
                    $flowerbed_repo->save($garden_flowerbed, true);*/
        
                }
            
            }
   
        } else {
            $message = "   la méthode passée n'est pas en POST";
        }

        $response = new Response($message);

        return $response;
    }

}
