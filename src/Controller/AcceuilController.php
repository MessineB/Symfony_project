<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\LikePost;
use App\Repository\PostRepository;
use App\Repository\LikePostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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

   
    /**
     * Permet de like un post
     * 
     * @param Post $post
     * @param ObjectManager $manager
     * @param LikePostRepository $likeRepo
     * @return Response
     */
    #[Route('/post/{id}/like', name: 'post_like')]
    public function like(Post $post, ManagerRegistry $manager , LikePostRepository $likeRepo) : Response  
    {
        $user = $this->getUser();
        $entityManager = $manager->getManager();
        if (!$user )  return $this->redirectToRoute('main_page', [], Response::HTTP_SEE_OTHER);

        if ($post->isLikedByUser($user)) {
            $like = $likeRepo->findOneBy([
                'post' => $post ,
                'user' => $user
            ]);
           
            $entityManager->remove($like);
            $entityManager->flush();

            return $this->redirectToRoute('main_page', [], Response::HTTP_SEE_OTHER);
        }
        

        $like = new LikePost() ;
        $like->setPost($post)
             ->setUser($user);
        $entityManager->persist($like);
        $entityManager->flush();

        return $this->redirectToRoute('main_page', [], Response::HTTP_SEE_OTHER);
    }

    }
    // #[Route('/ajoutlikepost', name: 'ajoutlikepost')]
    // public function index() {
    //     return 
    // }


// POUR LA FONCTION D'AURELIEN !
// <a href="{{path("NOM_DE_LA_PAGE", "id":post.id) }}" > Voir post </a>   