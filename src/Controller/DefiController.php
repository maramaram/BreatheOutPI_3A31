<?php

namespace App\Controller;
use App\Form\DefiType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Defi;
use App\Repository\DefiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;


class DefiController extends AbstractController
{
 

    #[Route('/fDefi', name: 'de_ftest')]
    public function findex(DefiRepository $a): Response
    {
        $exo = $a->findAll();
        return $this->render('defi/index.html f.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo
        ]);
    }

    #[Route('/bDefi', name: 'de_btest')]
    public function bindex(DefiRepository $a): Response
    {
        $exo = $a->findAll();
        return $this->render('defi/index.html b.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo
        ]);
    }

    #[Route('/bDefi/d/{id}', name: 'de_dex')]
    public function supprimer(EntityManagerInterface $e,$id,DefiRepository $a)
    {
        $weza=$a->find($id);
        $e->remove($weza);
        $e->flush();
        return $this->redirectToRoute('de_btest');
        }


        #[Route('/bDefi/a/', name: 'de_add')]
    public function ajouter(Request $r, EntityManagerInterface $e): Response
    {
        $Defi = new Defi();
        $form = $this->createForm(DefiType::class, $Defi);
        $form->handleRequest($r);
        
        if ($form->isSubmitted() && $form->isValid()) {
         
            $e->persist($Defi);
            $e->flush();
            // Redirigez l'utilisateur vers une autre page après l'ajout réussi
            return $this->redirectToRoute('de_btest');
        }
        
        // Affichez le formulaire dans le template
        return $this->render('defi/Ajouter.html.twig', [
            'form' => $form->createView(),
            'info' => 'Ajouter Defi'
        ]);
    }


    #[Route('/bDefi/m/{id}', name: 'de_mod')]
    public function modifier(EntityManagerInterface $e,DefiRepository $a,$id,Request $r): Response
    {
  $weza=$a->find($id);
  $form=$this->createForm(DefiType::class,$weza);
  $form->handleRequest($r);
  if($form->isSubmitted() && $form->isValid())
  {   // Définissez le répertoire où vous souhaitez stocker les images téléchargées
   
    $e->persist($weza);
      $e->flush();
      return $this->redirectToRoute('de_btest');
  }
 return $this->renderForm('defi/Modifier.html.twig',['form'=>$form,'info'=>'mod Defi']);
        }
 
      /*  #[Route('/dsearch', name: 'dsearch', methods: ['POST'])]
        public function rechercher(Request $request): Response
        {
            // Récupérer les données du formulaire de recherche
            $termeRecherche = $request->request->get('search');
            
            // Créer une instance de l'EntityManager
            $entityManager = $this->getDoctrine()->getManager();
            
            // Construire une requête DQL avec une correspondance partielle (LIKE)
            $query = $entityManager->createQueryBuilder()
                ->select('e')
                ->from(Defi::class, 'e')
                ->where('e.nom LIKE :termeRecherche')
                ->setParameter('termeRecherche', '%' . $termeRecherche . '%')
                ->getQuery();
            
            // Exécuter la requête et récupérer les résultats
            $resultats = $query->getResult();
            
            // Convertir les résultats en un tableau associatif pour le format JSON
            $Defis = [];
            foreach ($resultats as $Defi) {
                // Vérifier si le nom de l'Defi correspond à la recherche
                if (strpos(strtolower($Defi->getNom()), strtolower($termeRecherche)) !== false) {
                    // Ajouter l'Defi uniquement s'il répond aux critères
                    $Defis[] = [
                        'id' => $Defi->getId(),
                        'nom' => $Defi->getNom(),
                        'nd' => $Defi->getNd(),
                        'nbj' => $Defi->getNbj(),
                        'des' => $Defi->getDes(),
                        'ex' => $Defi->getEx(),
                        
                    ];
                }
            }
            
            // Retourner les résultats au format JSON
            return new JsonResponse(['weza' => $Defis]);
        }
        */
        }
