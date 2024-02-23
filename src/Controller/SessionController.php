<?php

namespace App\Controller;
use App\Form\SessionType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Session;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;


class SessionController extends AbstractController
{
 

    #[Route('/fSession', name: 'se_ftest')]
    public function findex(SessionRepository $a): Response
    {
        $exo = $a->findAll();
        return $this->render('session/index.html f.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo
        ]);
    }

    #[Route('/bSession', name: 'se_btest')]
    public function bindex(SessionRepository $a): Response
    {
        $exo = $a->findAll();
        return $this->render('session/index.html b.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo
        ]);
    }

    #[Route('/bSession/d/{id}', name: 'se_dex')]
    public function supprimer(EntityManagerInterface $e,$id,SessionRepository $a)
    {
        $weza=$a->find($id);
        $e->remove($weza);
        $e->flush();
        return $this->redirectToRoute('se_btest');
        }


        #[Route('/bSession/a/', name: 'se_add')]
    public function ajouter(Request $r, EntityManagerInterface $e): Response
    {
        $Session = new Session();
        $form = $this->createForm(SessionType::class, $Session);
        $form->handleRequest($r);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $directory = 'front/video';
   $directoryy = 'C:/Users/firas/PI_Dev/public/front/video';
            // Récupérez le fichier téléchargé à partir du formulaire
            $file = $form->get('vid')->getData();
           
            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid().'.'.$file->guessExtension();
           
            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);
           
            // Enregistrez le chemin de l'image dans votre base de données
            $Session->setVid($directory.'/'.$fileName);
           
            // Persistez l'entité dans la base de données
            $e->persist($Session);
            $e->flush();
            // Redirigez l'utilisateur vers une autre page après l'ajout réussi
            return $this->redirectToRoute('se_btest');
        }
        
        // Affichez le formulaire dans le template
        return $this->render('session/Ajouter.html.twig', [
            'form' => $form->createView(),
            'info' => 'Ajouter Session'
        ]);
    }


    #[Route('/bSession/m/{id}', name: 'se_mod')]
    public function modifier(EntityManagerInterface $e,SessionRepository $a,$id,Request $r): Response
    {
  $weza=$a->find($id);
  $form=$this->createForm(SessionType::class,$weza);
  $form->handleRequest($r);
  if($form->isSubmitted() && $form->isValid())
  {   // Définissez le répertoire où vous souhaitez stocker les images téléchargées
   // Définissez le répertoire où vous souhaitez stocker les images téléchargées
   $directory = 'front/video';
   $directoryy = 'C:/Users/firas/PI_Dev/public/front/video';
   // Récupérez le fichier téléchargé à partir du formulaire
   $file = $form->get('vid')->getData();
  
   // Générez un nom unique pour le fichier téléchargé
   $fileName = uniqid().'.'.$file->guessExtension();
  
   // Déplacez le fichier vers le répertoire de destination
   $file->move($directoryy, $fileName);
  
   // Enregistrez le chemin de l'image dans votre base de données
   $weza->setVid($directory.'/'.$fileName);
  
   // Persistez l'entité dans la base de données
    $e->persist($weza);
      $e->flush();
      return $this->redirectToRoute('se_btest');
  }
 return $this->renderForm('session/Modifier.html.twig',['form'=>$form,'info'=>'mod Session']);
        }


        #[Route('/fSession/detail/{id}', name: 're_detail')]
        public function detail($id,SessionRepository $a): Response
        {
            $weza=$a->find($id);
            return $this->render('session/Detail.html.twig', [
                'controller_name' => 'detail',
                'weza'=> $weza
            ]);
         }
        
 
        }
