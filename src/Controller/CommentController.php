<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\LikeComment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\LikeCommentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/post{id}', name: 'show_post', methods:['GET', 'POST'])]
    public function showPost(Post $post, ManagerRegistry $doctrine, Request $request, CommentRepository $commentRepo): Response
    {
         /** @var User $user */
        $user = $this->getUser();

        $addComment = new Comment();
        #On stocke notre formulaire préparé dans notre variable
        $form = $this->createForm(CommentType::class, $addComment)->handleRequest($request);

        if ($user) {

            $addComment->setCreatedAt(new DateTime);
            $addComment->setStatus('affiché');
            $addComment->setUser($user);
            $addComment->setPost($post);

            if ($form->isSubmitted() && $form->isValid()) {
        
                $entityManager = $doctrine->getManager();
                $entityManager->persist($addComment);
                $entityManager->flush();

            } elseif ($form->isSubmitted() && $form->getErrors()) {
                
                $this->addFlash('warning', 'Erreur il manque des champs !');

            }
        }

        return $this->render('comment/index.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'comments' => $commentRepo->findByPostId($post->getId())
        ]);
    }
    /**
     * Permet de like un post
     * 
     * @param Comment $comment
     * @param ObjectManager $manager
     * @param LikeCommentRepository $likeRepo
     * @return Response
     */
    #[Route('/comment/{id}', name: 'comment_like', methods:['GET'])]
    public function like(Comment $comment, ManagerRegistry $manager , LikeCommentRepository $likeRepo, Request $request) : Response  
    {
        $user = $this->getUser();
        $entityManager = $manager->getManager();
        if (!$user)  return $this->redirectToRoute('main_page', [], Response::HTTP_SEE_OTHER);
        
        if ($comment->isLikedByUser($user)) {
            $like = $likeRepo->findOneBy([
                'comment' => $comment ,
                'user' => $user
            ]);
           
            $entityManager->remove($like);
            $entityManager->flush();

            return $this->redirectToRoute(
                'show_post', ['id' => $comment->getPost()->getId()], Response::HTTP_SEE_OTHER);
        }
        

        $like = new LikeComment() ;
        $like->setComment($comment)
             ->setUser($user);
        $entityManager->persist($like);
        $entityManager->flush();

        return $this->redirectToRoute(
            'show_post', ['id' => $comment->getPost()->getId()], Response::HTTP_SEE_OTHER);
    }
}
