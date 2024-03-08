<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Form\LivreurType;
use App\Repository\LivreurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;


#[Route('/livreur')]
class LivreurController extends AbstractController
{
    #[Route('/', name: 'app_livreur_index', methods: ['GET'])]
    public function index(LivreurRepository $livreurRepository): Response
    {
        return $this->render('livreur/index.html.twig', [
            'livreurs' => $livreurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_livreur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livreur = new Livreur();
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $directory = 'Front/images';
        $directoryy = '../public/Front/images';//D:/projetpi/projetwebjava/public/Front/images

        // Récupérez le fichier téléchargé à partir du formulaire
        $file = $form->get('image')->getData();

        if ($file) {
            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid().'.'.$file->guessExtension();

            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);

            // Enregistrez le chemin de l'image dans votre base de données
            $livreur->setImage($directory.'/'.$fileName);
        }

        // Persistez l'entité dans la base de données
        $entityManager->persist($livreur);
        $entityManager->flush();

        return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
    }

        return $this->renderForm('livreur/new.html.twig', [
            'livreur' => $livreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livreur_show', methods: ['GET'])]
    public function show(Livreur $livreur): Response
    {
        return $this->render('livreur/show.html.twig', [
            'livreur' => $livreur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livreur_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Livreur $livreur, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(LivreurType::class, $livreur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $directory = 'Front/images';
        $directoryy = '../public/Front/images';

        // Récupérez le fichier téléchargé à partir du formulaire
        $file = $form->get('image')->getData();

        if ($file) {
            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid().'.'.$file->guessExtension();

            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);

            // Enregistrez le chemin de l'image dans votre base de données
            $livreur->setImage($directory.'/'.$fileName);
        }

        // Persistez l'entité dans la base de données
        $entityManager->persist($livreur);
        $entityManager->flush();

        return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('livreur/edit.html.twig', [
        'livreur' => $livreur,
        'form' => $form,
    ]);
}



    #[Route('/{id}', name: 'app_livreur_delete', methods: ['POST'])]
    public function delete(Request $request, Livreur $livreur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livreur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livreur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/showfront/{id}/{idcommande}', name: 'app_livreur_showfront', methods: ['GET'])]
    public function showfront(Livreur $livreur, ?int $idcommande = null): Response
    {
        return $this->render('livreur/showfront.html.twig', [
            'livreur' => $livreur,
            'idcommande' => $idcommande,
        ]);
    }
}
