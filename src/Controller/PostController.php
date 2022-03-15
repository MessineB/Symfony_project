<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/questions', name: 'questions')]
    public function index(): Response
    {
        return $this->render('questions.html.twig');
}
}