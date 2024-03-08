<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use App\Form\AddProduitsType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
class ProduitsController extends AbstractController
{
    #[Route('/produits', name: 'app_produits')]
    public function index(ProduitsRepository $ProduitsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $authorss = $ProduitsRepository->findAll();
        $pagination = $paginator->paginate(
            $authorss,
            $request->query->getInt('page', 1), // Get page number from the request, default to 1
            4// Number of items per page
        );

        return $this->render('pfront/pfront.html.twig', [
            'controller_name' => 'ProduitsController',
            'produits' => $pagination,

            'pagination' => $pagination, // Passer la variable pagination à Twig
        ]);
    }


    #[Route('/recherche', name: 'app_recherche_produits')]
    public function recherche(Request $request, ProduitsRepository $produitsRepository): JsonResponse
    {
        $term = $request->query->get('term');
        $resultats = $produitsRepository->rechercheProduits($term); // À définir dans le repository

        $data = [];
        foreach ($resultats as $resultat) {
            $data[] = [
                'id' => $resultat->getId(),
                'nom' => $resultat->getNom(),
                'description' => $resultat->getDescription(),
                // Ajoutez d'autres champs selon vos besoins
            ];
        }

        return new JsonResponse(['resultats' => $data]);
    }


    #[Route('/bproduits', name: 'app_produitts')]
    public function findex(ProduitsRepository $ProduitsRepository): Response
    {

        $weza=$ProduitsRepository->findAll();
        return $this->render('pback/pback.html.twig', [
            'controller_name' => 'ProduitsController',
            'produits' => $weza,
        ]);
    }

    #[Route('/Produits/pajouter', name: 'app_Produits_pajouter')]
    public function pajouter(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $Produits = new Produits();

        $form = $this->createForm(AddProduitsType::class, $Produits);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $directory = 'Front/images';
            $directoryy = 'C:/Users/bouaz/ThirdProject/public/Front/images';
            // Récupérez le fichier téléchargé à partir du formulaire
            $file = $form->get('image')->getData();

            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid().'.'.$file->guessExtension();

            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);

            // Enregistrez le chemin de l'image dans votre base de données
            $Produits->setimage($directory.'/'.$fileName);
            $entityManagerInterface->persist($Produits);
            $entityManagerInterface->flush();
            // Persistez l'entité dans la base de données
            return $this->redirectToRoute('app_produitts');
        }

        return $this->renderForm('Produits/newp.html.twig', ['form' => $form, 'info' => 'Add Produits']);
    }
    #[Route('/Produits/Pdelete/{id}', name: 'app_Produits_Pdelete')]
    public function Pdelete($id ,EntityManagerInterface $entityManagerInterface , ProduitsRepository $ProduitsRepository)
    {
        $Produits = $ProduitsRepository->find($id);
        $entityManagerInterface->remove($Produits);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_produitts');
        dd($Produits);

    }
    #[Route('/Produits/Pedit/{id}', name: 'app_Produits_Pedit')]
    public function Pedit(Request $request, $id, EntityManagerInterface $entityManagerInterface, ProduitsRepository $ProduitsRepository)
    {
        $Produits = $ProduitsRepository->find($id);
        $form = $this->createForm(AddProduitsType::class, $Produits);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifiez si un fichier a été téléchargé
            $file = $form->get('image')->getData();

            if ($file) {
                $directory = 'Front\images';
                $directoryy = 'C:/Users/bouaz/ThirdProject/public/Front/images';

                // Générez un nom unique pour le fichier téléchargé
                $fileName = uniqid().'.'.$file->guessExtension();

                // Déplacez le fichier vers le répertoire de destination
                $file->move($directoryy, $fileName);

                // Enregistrez le chemin de l'image dans votre base de données
                $Produits->setimage($directory.'/'.$fileName);
            }

            // Persistez l'entité dans la base de données
            $entityManagerInterface->persist($Produits);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_produitts');
        }

        return $this->renderForm('Produits/newp.html.twig', ['form' => $form, 'info' => 'Edit Produits']);
    }

    #[Route('/bproduits/generate-pdf', name: 'app_produits_generate_pdf')]
    public function generatePdf(Request $request, ProduitsRepository $produitsRepository): Response
    {
        // Récupérez tous les produits depuis la base de données
        $produitsList = $produitsRepository->findAll();

        // Créez une instance de Dompdf avec les options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);

        // Récupérez le contenu HTML à partir d'un template Twig
        $html = $this->renderView('pback/pdf_template.html.twig', [
            'produitsList' => $produitsList,
        ]);

        // Chargez le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendre le PDF
        $dompdf->render();

        // Récupérer le contenu du PDF
        $pdfContent = $dompdf->output();

        // Créez une réponse avec le contenu PDF
        $response = new Response($pdfContent);

        // Configurez les en-têtes pour indiquer qu'il s'agit d'un fichier PDF à télécharger
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="produits.pdf"');

        return $response;
    }




}

