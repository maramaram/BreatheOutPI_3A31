<?php

namespace App\Controller;
use App\Form\CommentType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
class CommentController extends AbstractController
{

    #[Route('/bComment', name: 'co_btest')]
    public function bindco(CommentRepository $a): Response
    {
        $coo = $a->findAll();
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'TestController',
            'weza'=> $coo
        ]);
    }

    #[Route('/bComment/d/{id}', name: 'co_dex')]
    public function supprimer(EntityManagerInterface $e,$id,CommentRepository $a)
    {
        $weza=$a->find($id);
        $e->remove($weza);
        $e->flush();
        return $this->redirectToRoute('co_btest');
        }

        #[Route('/fComment/d/{id}', name: 'fco_dex')]
    public function supprimerf(EntityManagerInterface $e,$id,CommentRepository $a)
    {
        $comment = $a->find($id);

        if (!$comment) {
            // Handle the case when the comment is not found
            // You can redirect or show an error message
            return $this->redirectToRoute('poco_ftest');
        }
        
        $postId = $comment->getPost()->getId();
    
        $e->remove($comment);
        $e->flush();
    
        return $this->redirectToRoute('poco_ftest', ['id' => $postId]);
        }


        #[Route('/bComment/a/', name: 'co_add')]
    public function ajouter(Request $r, EntityManagerInterface $e): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }
        $Comment = new Comment();
        $form = $this->createForm(CommentType::class, $Comment);
        $form->handleRequest($r);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $Comment->setnbLikes('0');
            $Comment->setUser($user);
            $e->persist($Comment);
            $e->flush();

            // Redirigez l'utilisateur vers une autre page après l'ajout réussi
            return $this->redirectToRoute('co_btest');
        }
        
        // Affichez le formulaire dans le template
        return $this->render('Comment/Ajouter.html.twig', [
            'form' => $form->createView(),
            'info' => 'Ajouter Comment'
        ]);
    }


    #[Route('/fComment/a/{id}', name: 'cof_add')]
    public function ajouterF(Request $r, EntityManagerInterface $e): Response
    {

        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }
        $Comment = new Comment();
        $form = $this->createForm(CommentType::class, $Comment);
        $form->handleRequest($r);
        $Post = $e->getRepository(Post::class)->find($r->get('id'));
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $forbiddenWords = ['door', 'window', 'sky'];
            $content = strtolower($Comment->getContenu());
    
            foreach ($forbiddenWords as $word) {
                if (strpos($content, $word) !== false) {
                    $this->addFlash('danger', 'Le commentaire contient un mot interdit.');
                    return $this->redirectToRoute('poco_ftest', ['id' => $Post->getId()]);
                }
            }

            $Comment->setUser($user);
            $Comment->setnbLikes(0);
            $Comment->setPost($Post);

            $e->persist($Comment);
            $e->flush();
    
            // Redirect the user to another page after successful addition
            return $this->redirectToRoute('poco_ftest', ['id' => $Post->getId()]);
        }
    
        // If form is not submitted or invalid, render the form again
        return $this->render('post/AjouterCF.html.twig', [
            'form' => $form->createView(),
            'info' => 'Ajouter Comment'
        ]);
    }



    #[Route('/bComment/m/{id}', name: 'co_mod')]
    public function modifier(EntityManagerInterface $e,CommentRepository $a,$id,Request $r): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }
  $weza=$a->find($id);
  $form=$this->createForm(CommentType::class,$weza);
  $form->handleRequest($r);
  if($form->isSubmitted() && $form->isValid())
  {
      $weza->setUser($user);
    $e->persist($weza);
     $e->flush();
      return $this->redirectToRoute('co_btest');
  }
  return $this->renderForm('Comment/Modifier.html.twig',['form'=>$form,'info'=>'mod Comment']);
        }



        #[Route('/bPost/m/{id}', name: 'fco_mod')]
        public function modifierf(EntityManagerInterface $e,CommentRepository $a,$id,Request $r): Response
        {
            $user = $this->getUser();
            if (!$user) {
                throw $this->createNotFoundException('No ID found');
            }
      $comment = $a->find($id);
      $postId = $comment->getPost()->getId();
      $weza=$a->find($id);
      $form=$this->createForm(CommentType::class,$weza);
      $form->handleRequest($r);
            $weza->setUser($user);
      if($form->isSubmitted() && $form->isValid())
      { 
        $e->persist($weza);
         $e->flush();
         return $this->redirectToRoute('poco_ftest', ['id' => $postId]);
      }
      return $this->renderForm('Post/ModifierC.html.twig',['form'=>$form,'info'=>'mod Comment']);
            }
    


            #[Route('/fComment/like/{id}', name: 'fco_like')]
            public function likeCommentAction(EntityManagerInterface $em, $id, CommentRepository $commentRepository): Response
            {
                $comment = $commentRepository->find($id);
            
                // Vérifiez si le commentaire existe
                if ($comment) {
                    // Incrémentez le nombre de likes
                    $comment->setnbLikes($comment->getnbLikes() + 1);
            
                    // Enregistrez les modifications en base de données
                    $em->persist($comment);
                    $em->flush();
                }
            
                // Redirigez ou retournez une réponse en fonction de vos besoins
                return $this->redirectToRoute('poco_ftest', ['id' => $comment->getPost()->getId()]);
            }
            


        
}
