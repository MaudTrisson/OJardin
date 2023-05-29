<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Form\AdviceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdviceController extends AbstractController
{
    #[Route('/advice', name: 'app_advice')]
    public function index(): Response
    {
        return $this->render('advice/index.html.twig', [
            'controller_name' => 'AdviceController',
        ]);
    }

    #[Route('/advice/new', name: 'create_advice')]
    public function create(): Response
    {
        $advice = new Advice();
        $form = $this->createForm(AdviceType::class, $advice);
        return $this->renderForm('advice/create.html.twig', [
            'advice_form' => $form,
        ]);
    }
}
