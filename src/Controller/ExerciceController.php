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




class ExerciceController extends AbstractController
{
    #[Route('/fexercice', name: 'ex_ftest')]
    public function findex(ExerciceRepository $a): Response
    {
        $exo = $a->findAll();
        return $this->render('exercice/index.html f.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo
        ]);
    }

    #[Route('/bexercice', name: 'ex_btest')]
    public function bindex(ExerciceRepository $a): Response
    {
        $exo = $a->findAll();
        return $this->render('exercice/index.html b.twig', [
            'controller_name' => 'TestController',
            'weza'=> $exo
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
            if($file !=null)
      
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
    $fileName = uniqid().'.'.$file->guessExtension();
    $fileNamee = uniqid().'.'.$file2->guessExtension();
    // Déplacez le fichier vers le répertoire de destination
    $file->move($directoryy, $fileName);
    $file2->move($directoryyg, $fileNamee);
    // Enregistrez le chemin de l'image dans votre base de données
    $weza->setImg($directory.'/'.$fileName);
    $weza->setGif($directoryg.'/'.$fileNamee);

    // Persistez l'entité dans la base de données
    $e->persist($weza);
      $e->flush();
      return $this->redirectToRoute('ex_btest');
  }
 return $this->renderForm('exercice/Modifier.html.twig',['form'=>$form,'info'=>'mod exercice']);
        }


        
        #[Route('/fexercice/detail/{id}', name: 'ex_detail')]
        public function detail($id,ExerciceRepository $a): Response
        {
            $weza=$a->find($id);
            return $this->render('exercice/Detail.html f.twig', [
                'controller_name' => 'detail',
                'weza'=> $weza
            ]);
         }
        

         #[Route('/search', name: 'search', methods: ['POST'])]
         public function rechercher(Request $request): Response
         {
             // Récupérer les données du formulaire de recherche
             $termeRecherche = $request->request->get('search');
             
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
