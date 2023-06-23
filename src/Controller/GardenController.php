<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Garden;
use App\Form\GardenType;
use App\Entity\Flowerbed;
use App\Entity\GardenUser;
use App\Entity\GardenFlowerbed;
use App\Repository\GardenUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

        //on met ces données en forme dans un tableau associatif qu'on enverra ensuite dans le template
        $flowerbeds_data = [];
        $flowerbed_data = [];
        
        foreach($flowerbeds as $flowerbed) {
            $flowerbed_data['formtype'] = $flowerbed->getFormtype();
            $flowerbed_data['top'] = $flowerbed->getTopy();
            $flowerbed_data['left'] = $flowerbed->getLeftx();
            $flowerbed_data['width'] = $flowerbed->getWidth();
            $flowerbed_data['height'] = $flowerbed->getHeight();
            $flowerbed_data['scalex'] = $flowerbed->getScalex();
            $flowerbed_data['scaley'] = $flowerbed->getScaley();
            $flowerbed_data['fill'] = $flowerbed->getFill();
            $flowerbed_data['fillOpacity'] = $flowerbed->getFillOpacity();
            $flowerbed_data['stroke'] = $flowerbed->getStroke();
            $flowerbed_data['flipangle'] = $flowerbed->getFlipangle();
            $flowerbed_data['shadowtype'] = $flowerbed->getShadowtype();

            if ($flowerbed->getGroundType()) {
                $flowerbed_data['groundtype'] = $flowerbed->getGroundType()->getId();
            };
            if ($flowerbed->getGroundAcidity()) {
                $flowerbed_data['groundacidity'] = $flowerbed->getGroundAcidity()->getId();
            };

            array_push($flowerbeds_data, $flowerbed_data);
        }

        return $this->render('garden/maintenance.html.twig', [
            'garden' => $garden,
            'flowerbeds' => $flowerbeds_data
        ]);
    }


}
