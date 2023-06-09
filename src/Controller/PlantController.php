<?php

namespace App\Controller;

use App\Entity\Plant;
use App\Form\PlantType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
            return new Response('la plante a bien été enregistré');
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
                return $this->render('edit/create.html.twig', [
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
}
