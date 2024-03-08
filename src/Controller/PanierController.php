<?php

namespace App\Controller;
use App\Entity\Produits;
use App\Entity\User;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Panier;
use App\Form\AddPanierType;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier as FlashyBundleFlashyNotifier;

use Symfony\Component\Messenger\MessageBusInterface;


class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(PanierRepository $PanierRepository): Response
    {
        $authorss=$PanierRepository->findAll();
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panier' => $authorss,

        ]);

    }
    #[Route('/ajp/{id}', name: 'ajp')]
    public function ajouterProduitAuPanier(
        PanierRepository $panierRepository,
        ProduitsRepository $produitsRepository,
        $id,
        EntityManagerInterface $entityManagerInterface,
        FlashyBundleFlashyNotifier $flashy
    ): Response {

        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }

        $produit = $produitsRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $panier = $panierRepository->find($user->getId());

        if (!$panier) {
            throw $this->createNotFoundException('Panier non trouvé');
        }

        // Vérifiez si le produit est déjà dans le panier
        $produitDansPanier = $panier->getListeproduits()->contains($produit);

        if (!$produitDansPanier) {
            // Ajoutez le produit au panier
            $panier->addListeproduit($produit);

            // Diminue la quantité en stock du produit
            $quantiteActuelle = $produit->getQuantiteStock();

            if ($quantiteActuelle > 0) {
                $produit->setQuantiteStock($quantiteActuelle - 1);

                // Enregistrez les modifications dans la base de données
                $entityManagerInterface->flush();
                $flashy->success('Article added!', 'http://your-awesome-link.com');
            } else {
                // Gérer le cas où la quantité en stock est épuisée
                $flashy->error('walah wfe !', 'http://your-awesome-link.com');
                // Vous pouvez choisir de lever une exception, afficher un message, etc.
            }
        }

        // Redirigez l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_produits');
    }




    #[Route('/panier/deleteproduit/{id}', name: 'app_panier_deleteproduit')]
    public function deleteProduit($id, EntityManagerInterface $entityManager, ProduitsRepository $produitsRepository, PanierRepository $panierRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }

        $produit = $produitsRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $panier = $panierRepository->find($user->getId());

        if (!$panier) {
            throw $this->createNotFoundException('Panier non trouvé');
        }

        // Retirez le produit du panier
        $panier->removeListeproduit($produit);

        // Incrémentez la quantitéStock de 1
        $produit->setQuantiteStock($produit->getQuantiteStock() + 1);

        // Enregistrez les modifications dans la base de données
        $entityManager->flush();

        // Redirigez l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_panier_ajouterpf');
    }

    #[Route('/panier/incrementerproduit/{id}', name: 'app_panier_incrementerproduit')]


    public function incrementerProduit($id, EntityManagerInterface $entityManager, ProduitsRepository $produitsRepository, PanierRepository $panierRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }

        $produit = $produitsRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $panier = $panierRepository->find($user->getId());

        if (!$panier) {
            throw $this->createNotFoundException('Panier non trouvé');
        }

        // Vérifiez si le produit est déjà dans le panier
        $produitsDansPanier = $produit->getPaniers();

        foreach ($produitsDansPanier as $produitDansPanier) {
            // Incrémentez la quantité du produit dans le panier
            $quantiteActuelle = $produitDansPanier->getQuantite();
            $produitDansPanier->setQuantite($quantiteActuelle + 1);

            // Diminue la quantité en stock du produit
            if ($produit->getQuantiteStock() > 0) {
                $produit->setQuantiteStock($produit->getQuantiteStock() + 1);

                // Enregistrez les modifications dans la base de données
                $entityManager->flush();
            } else {
                // Gérer le cas où la quantité en stock est épuisée
                // Vous pouvez choisir de lever une exception, afficher un message, etc.
            }
        }

        // Redirigez l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_panier_ajouterpf');
    }



    #[Route('/panier/decrementerproduit/{id}', name: 'app_panier_decrementerproduit')]
    public function decrementerProduit($id, EntityManagerInterface $entityManager, ProduitsRepository $produitsRepository, PanierRepository $panierRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }


        $produit = $produitsRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $panier = $panierRepository->find($user->getId());

        if (!$panier) {
            throw $this->createNotFoundException('Panier non trouvé');
        }

        // Vérifiez si le produit est dans le panier
        if ($panier->getListeproduits()->contains($produit)) {
            // Décrémentez la quantitéStock de 1, mais ne descendez jamais en dessous de 0
            $quantiteStockActuelle = $produit->getQuantiteStock();
            if ($quantiteStockActuelle > 0) {
                $produit->setQuantiteStock($quantiteStockActuelle - 1);
            }

            // Enregistrez les modifications dans la base de données
            $entityManager->flush();
        }

        // Redirigez l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_panier_ajouterpf');
    }

    #[Route('/valider/{id}', name: 'valider')]
    public function validerPanier(PanierRepository $panierRepository, ProduitsRepository $produitsRepository, $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }

        $produit = $produitsRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $panier = $panierRepository->find($user->getId());

        if (!$panier) {
            throw $this->createNotFoundException('Panier non trouvé');
        }

        // Vérifiez si le produit est déjà dans le panier
        $produitDansPanier = $panier->getListeproduits()->contains($produit);

        if (!$produitDansPanier) {
            // Ajoutez le produit au panier avec une quantité de 1
            $panier->setQuantite(1);
            $panier->addListeproduit($produit);

            // Diminue la quantité en stock du produit
            if ($produit->getQuantiteStock() > 0) {
                $produit->setQuantiteStock($produit->getQuantiteStock() - 1);
            } else {
                // Gérer le cas où la quantité en stock est épuisée
                // Vous pouvez choisir de lever une exception, afficher un message, etc.
            }

            // Enregistrez les modifications dans la base de données
            $entityManager->flush();
        }

        // Ajoutez ici la logique pour ajouter le produit au backend
        // ...

        // Redirigez l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_panier_ajouterpf');
    }



    #[Route('/panier/ajouterp', name: 'app_panier_ajouterp')]
    public function ajouterp(Request $request , EntityManagerInterface $entityManagerInterface)
    {


        $Panier= new Panier();

        $form=$this->createForm(AddPanierType::class,$Panier);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $entityManagerInterface->persist($Panier);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_panier');
        }
        return $this->renderForm('panier/panier.html.twig',['form'=>$form,'info'=>'Add Panier']);
        // dump($author);
        // die();
    }
    #[Route('/panier/ajouterpf/', name: 'app_panier_ajouterpf')]
    public function ajouterpf(Request $request , EntityManagerInterface $entityManagerInterface,PanierRepository $panierRepository)
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }

        $Panier = $panierRepository->find($user->getId());

        $entityManagerInterface->persist($Panier);
        $entityManagerInterface->flush();
        return $this->render('panier/formp.html.twig', [
            'controller_name' => 'PanierController',
            'Panier' => $Panier,
        ]);
        // dump($author);
        // die();
    }
    #[Route('/panier/editp/{id}', name: 'app_panier_editp')]
    public function fedit(Request $request,$id ,EntityManagerInterface $entityManagerInterface , PanierRepository $PanierRepository)
    {
        $Panier = $PanierRepository->find($id);
        $form=$this->createForm(AddPanierType::class,$Panier);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $entityManagerInterface->persist($Panier);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_panier');
        }
        return $this->renderForm('panier/panier.html.twig',['form'=>$form,'info'=>'Add Panier']);
        dump($Panier);
        die();
    }
    #[Route('/panier/editpf/{id}', name: 'app_panier_editpf')]
    public function feditf(Request $request,$id ,EntityManagerInterface $entityManagerInterface , PanierRepository $PanierRepository)
    {
        $Panier = $PanierRepository->find($id);
        $form=$this->createForm(AddPanierType::class,$Panier);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $entityManagerInterface->persist($Panier);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_panier');
        }
        return $this->renderForm('panier/formp.html.twig',['form'=>$form,'info'=>'Add Panier']);
        dump($Panier);
        die();
    }
    #[Route('/panier/deletep/{id}', name: 'app_panier_deletep')]
    public function Pdelete($id ,EntityManagerInterface $entityManagerInterface , PanierRepository $PanierRepository)
    {
        $Panier = $PanierRepository->find($id);
        $entityManagerInterface->remove($Panier);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_panier');
        dd($Panier);

    }


}
