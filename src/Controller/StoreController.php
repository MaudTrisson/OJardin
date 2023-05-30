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
            return $this->renderForm('store/create.html.twig', [
                'store_form' => $form,
            ]);
        }
        
        
    }
}
