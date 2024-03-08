<?php

namespace App\Controller;
use App\Form\ExerciceType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Exercice;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use OpenAI;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\String\ByteString;
use FPDF;

class ExerciceController extends AbstractController
{


    
    #[Route('/exercice/pdf', name: 'app_exercice_pdf')]
    public function pdf(ExerciceRepository $exerciceRepository)
    {
        // Récupérer les exercices depuis la base de données
        $exercices = $exerciceRepository->findAll();
    
        // Créer un nouveau document PDF
        $pdf = new FPDF();
    
        // Ajouter une page en mode paysage
        $pdf->AddPage('L');
        // Définir la police et la taille du texte
        $pdf->SetFont('Arial', '', 10);
    
        // Ajouter un titre en gras avec une taille de police plus grande
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Liste des exercices', 0, 1, 'C');
        $pdf->Cell(0, 10, '', 0, 1); // Ajouter un espace après le titre
    
        // Ajouter les en-têtes de colonne avec des couleurs de fond et de texte
        $pdf->SetFillColor(150, 150, 150);
        $pdf->SetTextColor(255);
        $pdf->Cell(20, 20, 'ID', 1, 0, 'C', true);
        $pdf->Cell(50, 20, 'Nom', 1, 0, 'C', true);
        $pdf->Cell(80, 20, 'Description', 1, 0, 'C', true);
        $pdf->Cell(40, 20, 'Muscule cible', 1, 0, 'C', true);
        $pdf->Cell(30, 20, 'Niveau', 1, 1, 'C', true);
    
        // Ajouter les données des exercices
        $pdf->SetFont('Arial', '', 10);
        foreach ($exercices as $exercice) {
            $id = $exercice->getId();
            $nom = $exercice->getNom();
            $des = $exercice->getDes();
            $mc = $exercice->getMc();
            $nd = $exercice->getNd();
    
            // Ajuster la taille des colonnes pour éviter les débordements
            $nom = $pdf->GetStringWidth($nom) > 40 ? substr($nom, 0, 20) . '...' : $nom;
            $des = $pdf->GetStringWidth($des) > 70 ? substr($des, 0, 40) . '...' : $des;
    
            // Utiliser des couleurs différentes pour chaque ligne
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0);
            $pdf->Cell(20, 20, $id, 1, 0, 'C', true);
            $pdf->Cell(50, 20, $nom, 1, 0, 'L', true);
            $pdf->Cell(80, 20, $des, 1, 0, 'L', true);
            $pdf->Cell(40, 20, $mc, 1, 0, 'C', true);
            $pdf->Cell(30, 20, $nd, 1, 1, 'C', true);

            
            return $this->redirectToRoute('ex_btest');
    
        }
    
        // Centrer le tableau sur la page
        $pdf->SetXY(($pdf->GetPageWidth() - $pdf->GetStringWidth('Liste des exercices')) / 2, $pdf->GetY());
    
        // Sauvegarder le PDF sur le serveur
        $pdfPath = 'C:/Users/Vayso/OneDrive/Bureau/e/Firjjj/public/Front/images/exo/exercices.pdf';
        $pdf->Output('F', $pdfPath);
    
        // Renvoyer le PDF en tant que réponse
        return new Response(
            $pdf->Output('S'), // Renvoyer le PDF sous forme de chaîne
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="exercices.pdf"'
            ]
        );
    }
    




    #[Route('/fexercice', name: 'ex_ftest')]
    public function findex(ExerciceRepository $a,Request $request, PaginatorInterface $paginator,EntityManagerInterface $entityManager): Response
    {
        //$exo = $a->findAll();

        $query = $entityManager->createQueryBuilder()
        ->select('e')
        ->from(Exercice::class, 'e')
        ->getQuery();


        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            2
        );
        return $this->render('exercice/index.html f.twig', [
            'controller_name' => 'TestController',
            //'weza'=> $exo,
            'pagination'=> $pagination
        ]);
    }

    #[Route('/bexercice', name: 'ex_btest')]
    public function bindex(ExerciceRepository $a ,Request $request, PaginatorInterface $paginator,EntityManagerInterface $entityManager): Response
    {
      
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
        return $this->render('exercice/index.html b.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo,
            'f'=> $f,
            'm'=> $m,
            'd'=> $d
        ]);
    }

    #[Route('/bexercice/d/{id}', name: 'ex_dex')]
    public function supprimer(EntityManagerInterface $e,$id,ExerciceRepository $a)
    {
        $weza=$a->find($id);
        $e->remove($weza);
        $e->flush();
        return $this->redirectToRoute('ex_btest');
        }


        #[Route('/bexercice/a/', name: 'ex_add')]
    public function ajouter(Request $r, EntityManagerInterface $e): Response
    {
        $Exercice = new Exercice();
        $form = $this->createForm(ExerciceType::class, $Exercice);
        $form->handleRequest($r);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Définissez le répertoire où vous souhaitez stocker les images téléchargées
            $directory = 'Front/images/exo';
            $directoryy = 'C:/Users/Vayso/OneDrive/Bureau/e/Firjjj/public/Front/images/exo';
            $directoryg = 'Front/images/exo/gif';
            $directoryyg = 'C:/Users/Vayso/OneDrive/Bureau/e/Firjjj/public/Front/images/exo/gif';
            // Récupérez le fichier téléchargé à partir du formulaire
            $file = $form->get('img')->getData();
            $file2 = $form->get('gif')->getData();
       
      
            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid().'.'.$file->guessExtension();
      
            $fileNamee = uniqid().'.'.$file2->guessExtension();
            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);
            $file2->move($directoryyg, $fileNamee);
            // Enregistrez le chemin de l'image dans votre base de données
            $Exercice->setImg($directory.'/'.$fileName);
            $Exercice->setGif($directoryg.'/'.$fileNamee);
            // Persistez l'entité dans la base de données
        
            $e->persist($Exercice);
            $e->flush();
            
            // Redirigez l'utilisateur vers une autre page après l'ajout réussi
            return $this->redirectToRoute('ex_btest');
        }
        
        // Affichez le formulaire dans le template
        return $this->render('exercice/Ajouter.html.twig', [
            'form' => $form->createView(),
            'info' => 'Ajouter exercice'
        ]);
    }


    #[Route('/bexercice/m/{id}', name: 'ex_mod')]
    public function modifier(EntityManagerInterface $e,ExerciceRepository $a,$id,Request $r): Response
    {
  $weza=$a->find($id);
  $form=$this->createForm(ExerciceType::class,$weza);
  $form->handleRequest($r);
  if($form->isSubmitted() && $form->isValid())
  {   // Définissez le répertoire où vous souhaitez stocker les images téléchargées
    $directory = 'Front/images/exo';
    $directoryy = 'C:/Users/Vayso/OneDrive/Bureau/e/Firjjj/public/Front/images/exo';
    $directoryg = 'Front/images/exo/gif';
    $directoryyg = 'C:/Users/Vayso/OneDrive/Bureau/e/Firjjj/public/Front/images/exo/gif';
    // Récupérez le fichier téléchargé à partir du formulaire
    $file = $form->get('img')->getData();
    $file2 = $form->get('gif')->getData();
   
    // Générez un nom unique pour le fichier téléchargé
    if($file !=null)
    {
    $fileName = uniqid().'.'.$file->guessExtension();
    $file->move($directoryy, $fileName);
    $weza->setImg($directory.'/'.$fileName);
    }
 else
    {
        $weza->setImg("Front/images/exo/developpe-couche-avec-elastique-exercice-musculation-pectoraux-maison.jpg");
    }

    if($file2 !=null)
    {
    $fileNamee = uniqid().'.'.$file2->guessExtension();
    $file2->move($directoryyg, $fileNamee);
    $weza->setGif($directoryg.'/'.$fileNamee);
    }
else
    {
        $weza->setGif("Front/images/exo/gif/chest-press-avec-sangles-suspension.gif");
    }
    // Déplacez le fichier vers le répertoire de destination
    
  
    // Enregistrez le chemin de l'image dans votre base de données
 

    // Persistez l'entité dans la base de données
    $e->persist($weza);
      $e->flush();
      return $this->redirectToRoute('ex_btest');
  }
 return $this->renderForm('exercice/Modifier.html.twig',['form'=>$form,'info'=>'mod exercice']);
        }


        
        #[Route('/fexercice/detail/{id}', name: 'ex_detail')]
        public function detail($id,ExerciceRepository $a ): Response
        {
            $weza=$a->find($id);
            return $this->render('exercice/Detail.html f.twig', [
                'controller_name' => 'detail',
                'weza'=> $weza
                
            ]);
         }


         #[Route('/chatgpt', name: 'chat_gpt')]
         public function chatgpt( ? string $question, ? string $response): Response
         {
             return $this->render('exercice/chatgpt.html.twig', [
                 'question' => $question,
                 'response' => $response
             ]);
         }
     
        

        #[Route('/chat', name: 'send_chat', methods:"POST")]
public function chat(Request $request): Response
{
    $question = $request->request->get('text');

    // Implémentation du chat GPT

    $myApiKey = $_ENV['OPENAI_KEY'];

    $client = OpenAI::client($myApiKey);

    $result = $client->completions()->create([
        'model' => 'gpt-3.5-turbo-instruct', // Utiliser le modèle gpt-3.5-turbo-instruct
        'prompt' => $question,
        'max_tokens' => 2008
    ]);

    $response=$result->choices[0]->text;
       
             
    return $this->forward('App\Controller\ExerciceController::chatgpt', [
       
        'question' => $question,
        'response' => $response
    ]);
}
           
       
           
        





         #[Route('/search', name: 'search', methods: ['POST'])]
public function rechercher(Request $request, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
{
    // Récupérer les données du formulaire de recherche
    $termeRecherche = $request->request->get('search');
    $muscleCible = $request->request->get('muscle'); // Récupérer le muscle ciblé

    // Créer une instance de l'EntityManager
    $entityManager = $this->getDoctrine()->getManager();
    
    // Construire une requête DQL avec une correspondance partielle (LIKE) et filtrer par muscle ciblé
    $query = $entityManager->createQueryBuilder()
        ->select('e')
        ->from(Exercice::class, 'e')
        ->where('e.nom LIKE :termeRecherche')
        ->andWhere('e.mc LIKE :muscleCible')
        ->setParameter('termeRecherche', '%' . $termeRecherche . '%')
        ->setParameter('muscleCible', '%' . $muscleCible . '%') // Passer le muscle ciblé à la requête
        ->getQuery();
    
    // Exécuter la requête et récupérer les résultats paginés
    $pagination = $paginator->paginate(
        $query,
        $request->query->get('page', 1),
        2
    );
    
    // Vérifier si des résultats paginés existent
   
    $resultats = $query->getResult();
    // Convertir les résultats paginés en un tableau associatif pour le format JSON
    $exercices = [];
    foreach ($pagination as $exercice) {
        // Vérifier si le nom de l'exercice correspond à la recherche
        if (strpos(strtolower($exercice->getNom()), strtolower($termeRecherche)) !== false) {
            // Ajouter l'exercice uniquement s'il répond aux critères
            $exercices[] = [
                'id' => $exercice->getId(),
                'nom' => $exercice->getNom(),
                'mc' => $exercice->getMc(),
                'nd' => $exercice->getNd(),
                'des' => $exercice->getDes(),
                'img' => $exercice->getImg(),
                'gif' => $exercice->getGif()
            ];
        }
    }
    
    // Retourner les résultats paginés et la variable de pagination au format JSON
    return new JsonResponse(['weza' => $exercices, 'pagination' => $pagination]);
}


#[Route('/searchh', name: 'searchh', methods: ['POST'])]
public function rechercheeer(Request $request): Response
{
    // Récupérer les données du formulaire de recherche
    $termeRecherche = $request->request->get('searchh');
    
    // Créer une instance de l'EntityManager
    $entityManager = $this->getDoctrine()->getManager();
    
    // Construire une requête DQL avec une correspondance partielle (LIKE)
    $query = $entityManager->createQueryBuilder()
        ->select('e')
        ->from(Exercice::class, 'e')
        ->where('e.nom LIKE :termeRecherche')
        ->setParameter('termeRecherche', '%' . $termeRecherche . '%')
        ->getQuery();
    
    // Exécuter la requête et récupérer les résultats
    $resultats = $query->getResult();
    
    // Convertir les résultats en un tableau associatif pour le format JSON
    $exercices = [];
    foreach ($resultats as $exercice) {
        // Vérifier si le nom de l'exercice correspond à la recherche
        if (strpos(strtolower($exercice->getNom()), strtolower($termeRecherche)) !== false) {
            // Ajouter l'exercice uniquement s'il répond aux critères
            $exercices[] = [
                'id' => $exercice->getId(),
                'nom' => $exercice->getNom(),
                'mc' => $exercice->getMc(),
                'nd' => $exercice->getNd(),
                'des' => $exercice->getDes(),
                'img' => $exercice->getImg(),
                'gif' => $exercice->getGif()
            ];
        }
    }
    
    // Retourner les résultats au format JSON
    return new JsonResponse(['weza' => $exercices]);
}
}
