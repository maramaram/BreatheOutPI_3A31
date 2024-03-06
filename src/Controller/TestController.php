<?php

namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Exercice;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request ;

class TestController extends AbstractController
{
    #[Route('/ftest', name: 'app_ftestt')]
    public function findex(ExerciceRepository $a): Response
    {
      
        return $this->render('basefront.html.twig', [
            'controller_name' => 'TestController',
           
        ]);
    }

    #[Route('/btest', name: 'app_btestt')]
    public function bindex(ExerciceRepository $a): Response
    {
       
        return $this->render('baseback.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/author/list/base/d/{id}', name: 'app_dext')]
    public function listbasesssgg(EntityManagerInterface $e,$id,ExerciceRepository $a)
    {
        $weza=$a->find($id);
        $e->remove($weza);
        $e->flush();
        return $this->redirectToRoute('app_btest');
        }
}
