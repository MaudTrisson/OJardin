<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Form\AdviceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdviceController extends AbstractController
{
    #[Route('/advice', name: 'advice')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $advices = $doctrine->getRepository(Advice::class)->findAll();
        return $this->render('advice/index.html.twig', [
            'advices' => $advices,
        ]);
    }

    #[Route('/advice/new', name: 'create_advice')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $advice = new Advice();
        $form = $this->createForm(AdviceType::class, $advice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //mettre ici les setter des données non remplies
            $entityManager = $doctrine->getManager();
            $entityManager->persist($advice);
            $entityManager->flush();
            $message = 'Le conseil a bien été enregistré';

            $advices = $doctrine->getRepository(Advice::class)->findAll();
            return $this->render('advice/index.html.twig', [
                'advices' => $advices,
                'message' => $message,
            ]);

        } else {
            return $this->render('advice/create.html.twig', [
                'adviceForm' => $form->createView(),
            ]);
        }
    }

    #[Route('/advice/edit/{id}', name: 'edit_advice')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Advice $advice, Request $request, ManagerRegistry $doctrine) {

        $form = $this->createForm(AdviceType::class, $advice);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            //Dans l'edit pas besoin du persist
            $entityManager->flush();
            return new Response('Le conseil a bien été mis à jour');
            } else {
                return $this->render('advice/edit.html.twig', [
                    'adviceForm' => $form->createView(),
                    'advice' => $advice,
                ]);
            }
    }

    #[Route('/advice/remove/{id}', name: 'remove_advice')]
    public function remove(Advice $advice, Request $request, ManagerRegistry $doctrine): Response {
        
        $entityManager = $doctrine->getManager();
        $entityManager->remove($advice);
        $entityManager->flush();
        return $this->redirectToRoute('advice');
    }
}
