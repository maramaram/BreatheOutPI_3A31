<?php

namespace App\Controller;
use DateTime;
use App\Form\ReservationType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use App\Repository\SessionRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Doctrine\ORM\QueryBuilder;

class ReservationController extends AbstractController
{
    
    #[Route('/TriCroissant', name: 'TriCroissant')]
    public function TriCroissant(ReservationRepository $eventRepository): Response
    {
    $event = $eventRepository->TriCroissant();
    return $this->render('reservation/index.html b.twig', [
        'weza' => $event
    ]);
    }

    #[Route('/TriDecroissant', name: 'TriDecroissant')]
    public function TriDecroissant(ReservationRepository $eventRepository): Response
    {
        $event = $eventRepository->TriDecroissant();
        return $this->render('reservation/index.html b.twig', [
            'weza' => $event
    ]);
    } 
    #[Route('/fReservation/ajou/{id}', name: 're_ftest')]
    public function findex(EntityManagerInterface $e,SessionRepository $s,UserRepository $u,$id): Response
    { 
        $weza = new Reservation();
      

        // Créez un nouvel objet DateTime pour représenter la date actuelle
        $date = new DateTime();
        
        // Passez cet objet DateTime à la méthode setDate
        $weza->setDate($date);
        $weza->setEtat("1");
                $weza->setUser($u->find(1));
                $weza->setSession($s->find($id));
                $e->persist($weza);
                $e->flush();
            
        return $this->redirectToRoute('se_ftest');
    }

    #[Route('/bReservation', name: 're_btest')]
    public function bindex(ReservationRepository $a): Response
    {
        $exo = $a->findAll();
        return $this->render('reservation/index.html b.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo
        ]);
    }

    #[Route('/bReservation/d/{id}', name: 're_dex')]
    public function supprimer(EntityManagerInterface $e,$id,ReservationRepository $a)
    {
        $weza=$a->find($id);
        $e->remove($weza);
        $e->flush();
        return $this->redirectToRoute('re_btest');
        }


        #[Route('/bReservation/a/', name: 're_add')]
    public function ajouter(Request $r, EntityManagerInterface $e): Response
    {
        $Reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $Reservation);
        $form->handleRequest($r);
        
        if ($form->isSubmitted() && $form->isValid()) {
          
            $e->persist($Reservation);
            $e->flush();
            // Redirigez l'utilisateur vers une autre page après l'ajout réussi
            return $this->redirectToRoute('re_btest');
        }
        
        // Affichez le formulaire dans le template
        return $this->render('reservation/Ajouter.html.twig', [
            'form' => $form->createView(),
            'info' => 'Ajouter Reservation'
        ]);
    }


    #[Route('/bReservation/m/{id}', name: 're_mod')]
    public function modifier(EntityManagerInterface $e,ReservationRepository $a,$id,Request $r): Response
    {
  $weza=$a->find($id);
  $form=$this->createForm(ReservationType::class,$weza);
  $form->handleRequest($r);
  if($form->isSubmitted() && $form->isValid())
  {   
    $e->persist($weza);
      $e->flush();
      return $this->redirectToRoute('re_btest');
  }
 return $this->renderForm('reservation/Modifier.html.twig',['form'=>$form,'info'=>'mod Reservation']);
        }
 
}
