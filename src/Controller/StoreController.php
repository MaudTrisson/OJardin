<?php

namespace App\Controller;

use App\Entity\Store;
use App\Form\StoreType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class StoreController extends AbstractController
{
    #[Route('/store', name: 'store')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $stores = $doctrine->getRepository(Store::class)->findAll();
        return $this->render('store/index.html.twig', [
            'stores' => $stores,
        ]);
    }

    #[Route('/store/new', name: 'create_store')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $store = new Store();
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //mettre ici les setter des données non remplies
            $entityManager = $doctrine->getManager();
            $entityManager->persist($store);
            $entityManager->flush();
            $message = 'Le magasin a bien été enregistré';

            $stores = $doctrine->getRepository(Store::class)->findAll();
            return $this->render('store/index.html.twig', [
                'stores' => $stores,
                'message' => $message,
            ]);

        } else {
            return $this->render('store/create.html.twig', [
                'storeForm' => $form->createView(),
            ]);
        }
        
        
    }

    #[Route('/store/edit/{id}', name: 'edit_store')]
    public function edit(Store $store, Request $request, ManagerRegistry $doctrine) {

        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            //Dans l'edit pas besoin du persist
            $entityManager->flush();
            return new Response('Le magasin a bien été mis à jour');
            } else {
                return $this->render('store/edit.html.twig', [
                    'storeForm' => $form->createView(),
                    'store' => $store,
                ]);
            }
    }

    #[Route('/store/remove/{id}', name: 'remove_store')]
    public function remove(Store $store, Request $request, ManagerRegistry $doctrine): Response {
        
        $entityManager = $doctrine->getManager();
        $entityManager->remove($store);
        $entityManager->flush();
        return $this->redirectToRoute('store');
    }
}
