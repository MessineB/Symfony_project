<?php

namespace App\Controller;

use App\Repository\PostRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'main_page')]
    public function index(PostRepository $posts): Response
    {

        return $this->render('index.html.twig', [
            "posts" => $posts->findByDate() 
        ]);
    }
}

// POUR LA FONCTION D'AURELIEN !
//<a href="{{path("show_post", "id":post.id) }}" > Voir post </a> 