<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'main_page')]
    public function index(PostRepository $posts , EntityManagerInterface $manager , Request $request): Response
    {   $post = new Post();
        $form = $this->createForm(PostType::class , $post)
        ->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setStatus("affiché");
            $post->setUser($this->getUser());
            $manager->persist($post);
            $manager->flush();
        }

        return $this->render('index.html.twig', [
            "posts" => $posts->findByDate() ,
            "form" => $form->createView()
        ]);
    }
    // #[Route('/ajoutlikepost', name: 'ajoutlikepost')]
    // public function index() {
    //     return 
    // }
}

// POUR LA FONCTION D'AURELIEN !
// <a href="{{path("NOM_DE_LA_PAGE", "id":post.id) }}" > Voir post </a> 