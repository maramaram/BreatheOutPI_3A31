<?php

namespace App\Controller;

use App\Form\PostType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;


class PostController extends AbstractController
{
    #[Route('/bPost', name: 'po_btest')]
    public function bindco(EntityManagerInterface $entityManager, PostRepository $postRepository): Response
    {
        // Fetch most viewed posts using native SQL
        $mostViewedPosts = $entityManager->getConnection()->executeQuery(
            'SELECT 
                DATE_FORMAT(date, "%Y-%m-%d") as publishDay, 
                title, 
                views 
            FROM 
                post p
            JOIN (
                SELECT 
                    DATE_FORMAT(date, "%Y-%m-%d") as publishDay, 
                    MAX(views) as maxViews 
                FROM 
                    post 
                GROUP BY 
                    publishDay
            ) maxViewsByDay ON DATE_FORMAT(p.date, "%Y-%m-%d") = maxViewsByDay.publishDay
                AND p.views = maxViewsByDay.maxViews
            ORDER BY 
                date ASC'
        )->fetchAllAssociative();

        $coo = $postRepository->findAll(); // Assuming this is your existing logic

        return $this->render('post/index.html.twig', [
            'controller_name' => 'TestController',
            'weza' => $coo,
            'mostViewedPosts' => $mostViewedPosts, // Add mostViewedPosts variable
        ]);
    }





    
    #[Route('/fPost', name: 'po_ftest')]
    public function findco(PostRepository $a): Response
    {       
        $coo = $a->findAll();
        return $this->render('post/Front.html.twig', [
            'controller_name' => 'TestController',
            'weza'=> $coo
        ]);
    }

    #[Route('/fPostcomment/{id}', name: 'poco_ftest')]
    public function findcom(PostRepository $a,$id,EntityManagerInterface $e): Response
    {   
        $Post = $a->find($id);
        $Post->setViews($Post->getViews() + 1);
        $e->persist($Post);
        $e->flush();
        $coo = $a->find($id);
        return $this->render('post/postcomment.html.twig', [
            'controller_name' => 'TestController',
            'weza'=> $coo
        ]);
    }


    

    


    #[Route('/bPost/d/{id}', name: 'po_dex')]
    public function supprimer(EntityManagerInterface $e,$id,PostRepository $a)
    {
        $weza=$a->find($id);
        $e->remove($weza);
        $e->flush();
        return $this->redirectToRoute('po_btest');
        }


        #[Route('/bPost/f/f/{id}', name: 'fpo_dex')]
    public function supprimerf(EntityManagerInterface $e,$id,PostRepository $a)
    {
        $weza=$a->find($id);
        $e->remove($weza);
        $e->flush();
        return $this->redirectToRoute('po_ftest');
        }


        
        
    #[Route('/bPost/a/', name: 'po_add')]
    public function ajouter(Request $r, EntityManagerInterface $e): Response
    {
        $Post = new Post();
        $form = $this->createForm(PostType::class, $Post);
        $form->handleRequest($r);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $directory = 'Front/images/post';
            $directoryy = 'C:/Users/bouaz/ThirdProject/public/Front/images/post';
            $file = $form->get('image')->getData();
            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid().'.'.$file->guessExtension();
            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);
            // Enregistrez le chemin de l'image dans votre base de données
            $Post->setImage($directory.'/'.$fileName);
             $e = $this->getDoctrine()->getManager();

             // Article creation date and time
             $Post->setdate(new \DateTimeImmutable());
 
             
 
             // Set views number to 1
             $Post->setViews('1');
 

            $e->persist($Post);
            $e->flush();
            
            // Redirigez l'utilisateur vers une autre page après l'ajout réussi
            return $this->redirectToRoute('po_btest');
        }
        
        // Affichez le formulaire dans le template
        return $this->render('Post/Ajouter.html.twig', [
            'form' => $form->createView(),
            'info' => 'Ajouter Post'
        ]);
    }



    #[Route('/fPost/a/', name: 'fpo_add')]
    public function ajouterf(UserRepository $repository,Request $r, EntityManagerInterface $e): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }
        $Post = new Post();
        $form = $this->createForm(PostType::class, $Post);
        $form->handleRequest($r);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $directory = 'Front/images/post';
            $directoryy = 'C:/Users/bouaz/ThirdProject/public/Front/images/post';
            $file = $form->get('image')->getData();
            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid().'.'.$file->guessExtension();
            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);
            // Enregistrez le chemin de l'image dans votre base de données
            $Post->setImage($directory.'/'.$fileName);

             $e = $this->getDoctrine()->getManager();

             // Article creation date and time
            $Post->setAuthor($user);
             $Post->setdate(new \DateTimeImmutable());
             $Post->setViews('0');
            $e->persist($Post);
            $e->flush();
            $this->addFlash('success', 'Post successfully added!');
            // Redirigez l'utilisateur vers une autre page après l'ajout réussi
            return $this->redirectToRoute('po_ftest');
        }
        
        // Affichez le formulaire dans le template
        return $this->render('Post/AjouterF.html.twig', [
            'form' => $form->createView(),
            'info' => 'Ajouter Post'
        ]);
    }

    #[Route('/bPost/f/{id}', name: 'fppo_mod')]
    public function modifierf(EntityManagerInterface $e,PostRepository $a,$id,Request $r): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }
  $weza=$a->find($id);
  $form=$this->createForm(PostType::class,$weza);
  $form->handleRequest($r);
  if($form->isSubmitted() && $form->isValid())
  { 
    $directory = 'Front/images/post';
    $directoryy = 'C:/Users/bouaz/ThirdProject/public/Front/images/post';
    $file = $form->get('image')->getData();
    // Générez un nom unique pour le fichier téléchargé
    $fileName = uniqid().'.'.$file->guessExtension();
    // Déplacez le fichier vers le répertoire de destination
    $file->move($directoryy, $fileName);
    // Enregistrez le chemin de l'image dans votre base de données
    $weza->setImage($directory.'/'.$fileName);
      $weza->setAuthor($user);
    $e->persist($weza);
     $e->flush();
      return $this->redirectToRoute('po_ftest');
  }
  return $this->renderForm('post/Modifier.html.twig',['form'=>$form,'info'=>'mod post']);
        }




        



}


