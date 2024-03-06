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
use Symfony\Component\HttpFoundation\JsonResponse;
use FPDF;
use Knp\Component\Pager\PaginatorInterface;

class DefiController extends AbstractController
{
 

    #[Route('/defi/pdf', name: 'app_defi_pdf')]
    public function pdf(DefiRepository $defiRepository)
    {
        // Récupérer les défis depuis la base de données
        $defis = $defiRepository->findAll();
        
        // Créer un nouveau document PDF
        $pdf = new FPDF();
        
        // Ajouter une page en mode paysage
        $pdf->AddPage('L');
        
        // Définir la police et la taille du texte
        $pdf->SetFont('Arial', '', 10);
        
        // Ajouter un titre en gras avec une taille de police plus grande
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Liste des defis', 0, 1, 'C');
        $pdf->Cell(0, 10, '', 0, 1); // Ajouter un espace après le titre
        
        // Ajouter les en-têtes de colonne avec des couleurs de fond et de texte
        $pdf->SetFillColor(150, 150, 150);
        $pdf->SetTextColor(255);
        $pdf->Cell(20, 20, 'ID', 1, 0, 'C', true);
        $pdf->Cell(50, 20, 'Nom', 1, 0, 'C', true);
        $pdf->Cell(80, 20, 'Description', 1, 0, 'C', true);
        $pdf->Cell(60, 20, 'Niveau de difficulte', 1, 0, 'C', true);
        $pdf->Cell(50, 20, 'Nombre de jours', 1, 1, 'C', true);
        
        // Ajouter les données des défis
        $pdf->SetFont('Arial', '', 10);
        foreach ($defis as $defi) {
            $id = $defi->getId();
            $nom = $defi->getNom();
            $des = $defi->getDes();
            $nd = $defi->getNd();
            $nbj = $defi->getNbj();
            
            // Ajuster la taille des colonnes pour éviter les débordements
            $nom = $pdf->GetStringWidth($nom) > 40 ? substr($nom, 0, 20) . '...' : $nom;
            $des = $pdf->GetStringWidth($des) > 70 ? substr($des, 0, 40) . '...' : $des;
            
            // Utiliser des couleurs différentes pour chaque ligne
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0);
            $pdf->Cell(20, 20, $id, 1, 0, 'C', true);
            $pdf->Cell(50, 20, $nom, 1, 0, 'L', true);
            $pdf->Cell(80, 20, $des, 1, 0, 'L', true);
            $pdf->Cell(60, 20, $nd, 1, 0, 'C', true);
            $pdf->Cell(50, 20, $nbj, 1, 1, 'C', true);



        }
        
        // Centrer le tableau sur la page
        $pdf->SetXY(($pdf->GetPageWidth() - $pdf->GetStringWidth('Liste des défis')) / 2, $pdf->GetY());
        
        // Sauvegarder le PDF sur le serveur
        $pdfPath = 'C:/Users/bouaz/ThirdProject/public/Front/images/exo/defi.pdf';
        $pdf->Output('F', $pdfPath);
        
        // Renvoyer le PDF en tant que réponse
        return new Response(
            $pdf->Output('S'), // Renvoyer le PDF sous forme de chaîne
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="defis.pdf"'
            ]
        );
    }




    #[Route('/fDefi', name: 'de_ftest')]
    public function findex(DefiRepository $a,Request $request, PaginatorInterface $paginator,EntityManagerInterface $entityManager): Response
    {
        $exo = $a->findAll();

        $query = $entityManager->createQueryBuilder()
        ->select('e')
        ->from(Defi::class, 'e')
        ->getQuery();


        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            2
        );
        return $this->render('defi/index.html f.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo,
            'pagination'=> $pagination
        ]);
    }

    #[Route('/bDefi', name: 'de_btest')]
    public function bindex(DefiRepository $a): Response
    {
        $exo = $a->findAll();
        $d=0;
        $m=0;
        $f=0;
        $exo = $a->findAll();
        for ($i = 0; $i < count($exo); $i++) {
            if ($exo[$i]->getNd() == 1) {
                $f++;
            }
            if ($exo[$i]->getNd() == 2) {
                $m++;
            }
            if ($exo[$i]->getNd() == 3) {
                $d++;
            }
        }
        return $this->render('defi/index.html b.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo,
            'f'=> $f,
            'm'=> $m,
            'd'=> $d
        ]);
    }

    #[Route('/bDefi/d/{id}', name: 'de_dex')]
    public function supprimer(EntityManagerInterface $e,$id,DefiRepository $a)
    {
        $weza=$a->find($id);
        $e->remove($weza);
        $e->flush();
        return $this->redirectToRoute('de_btest',);
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
 
        #[Route('/dsearch', name: 'dsearch', methods: ['POST'])]
        public function rechercher(Request $request, PaginatorInterface $paginator, EntityManagerInterface $entityManager): JsonResponse
        {
            // Récupérer les données du formulaire de recherche
            $termeRecherche = $request->request->get('dsearch');
            $dif = $request->request->get('difficulte'); // Modifier le nom du paramètre pour correspondre à celui envoyé depuis le front-end
            // Créer une instance de l'EntityManager
            $entityManager = $this->getDoctrine()->getManager();
            
            // Construire une requête DQL avec une correspondance partielle (LIKE)
            $query = $entityManager->createQueryBuilder()
                ->select('d', 'e') // Sélectionner également les exercices
                ->from(Defi::class, 'd')
                ->leftJoin('d.ex', 'e') // Joindre la relation avec les exercices
                ->where('d.nom LIKE :termeRecherche')
                ->andWhere('d.nd = :difficulte') // Modifier le nom du paramètre
                ->setParameter('termeRecherche', '%' . $termeRecherche . '%')
                ->setParameter('difficulte', $dif) // Modifier le nom du paramètre
                ->getQuery();
            
            // Exécuter la requête et récupérer les résultats
            
            $pagination = $paginator->paginate(
                $query,
                $request->query->get('page', 1),
                2
            );
            $resultats = $query->getResult();
            // Convertir les résultats en un tableau associatif pour le format JSON
            $Defis = [];
            foreach ($resultats as $Defi) {
                // Vérifier si le nom de l'Defi correspond à la recherche
                if (strpos(strtolower($Defi->getNom()), strtolower($termeRecherche)) !== false) {
                    // Initialiser un tableau pour les exercices de l'Defi
                    $exercices = [];
                    foreach ($Defi->getEx() as $exercice) {
                        // Ajouter les champs pertinents de chaque exercice
                        $exercices[] = [
                            'nom' => $exercice->getNom(),
                            'nd' => $exercice->getNd(),
                            'id' => $exercice->getId()
                            // Ajoutez d'autres champs d'exercice si nécessaire
                        ];
                    }
                    
                    // Ajouter l'Defi avec les exercices dans le tableau
                    $Defis[] = [
                        'id' => $Defi->getId(),
                        'nom' => $Defi->getNom(),
                        'nd' => $Defi->getNd(),
                        'nbj' => $Defi->getNbj(),
                        'des' => $Defi->getDes(),
                        'ex' => $exercices,
                    ];
                }
            }
            
            // Retourner les résultats au format JSON
            return new JsonResponse(['weza' => $Defis, 'pagination' => $pagination]);
        }












        #[Route('/dsearchh', name: 'dsearchh', methods: ['POST'])]
        public function rechercheeer(Request $request): JsonResponse
        {
            // Récupérer les données du formulaire de recherche
            $termeRecherche = $request->request->get('dsearchh');
            
            // Créer une instance de l'EntityManager
            $entityManager = $this->getDoctrine()->getManager();
            
            // Construire une requête DQL avec une correspondance partielle (LIKE)
            $query = $entityManager->createQueryBuilder()
                ->select('d', 'e') // Sélectionner également les exercices
                ->from(Defi::class, 'd')
                ->leftJoin('d.ex', 'e') // Joindre la relation avec les exercices
                ->where('d.nom LIKE :termeRecherche')
                ->setParameter('termeRecherche', '%' . $termeRecherche . '%')
                ->getQuery();
            
            // Exécuter la requête et récupérer les résultats
            $resultats = $query->getResult();
            
            // Convertir les résultats en un tableau associatif pour le format JSON
            $Defis = [];
            foreach ($resultats as $Defi) {
                // Vérifier si le nom de l'Defi correspond à la recherche
                if (strpos(strtolower($Defi->getNom()), strtolower($termeRecherche)) !== false) {
                    // Initialiser un tableau pour les exercices de l'Defi
                    $exercices = [];
                    foreach ($Defi->getEx() as $exercice) {
                        // Ajouter les champs pertinents de chaque exercice
                        $exercices[] = [
                            'nom' => $exercice->getNom(),
                            'nd' => $exercice->getNd(),
                            // Ajoutez d'autres champs d'exercice si nécessaire
                        ];
                    }
                    
                    // Ajouter l'Defi avec les exercices dans le tableau
                    $Defis[] = [
                        'id' => $Defi->getId(),
                        'nom' => $Defi->getNom(),
                        'nd' => $Defi->getNd(),
                        'nbj' => $Defi->getNbj(),
                        'des' => $Defi->getDes(),
                        'ex' => $exercices,
                    ];
                }
            }
            
            // Retourner les résultats au format JSON
            return new JsonResponse(['weza' => $Defis]);
        }
        
        }
