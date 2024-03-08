<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FPDF;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpClient\HttpClient;
use App\Entity\PdfFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use ApiPlatform\Api\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PanierRepository;
use App\Repository\LivreurRepository;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository,Request $request, PaginatorInterface $paginator,EntityManagerInterface $entityManager): Response
    {

        $query = $entityManager->createQueryBuilder()
        ->select('e')
        ->from(Commande::class, 'e')
        ->getQuery();


        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            2
        );
        include '../public/Back/uploadfichier.php';
        $filePath = '../public/Front/link.txt';
        $fileContent = file_get_contents($filePath);

        return $this->render('commande/index.html.twig', [
            'pagination' => $pagination,
            'fileContent' => $fileContent,
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/new/f', name: 'app_commande_neww', methods: ['GET', 'POST'])]
    public function auto(Request $request, EntityManagerInterface $entityManager,PanierRepository $panierRepository,LivreurRepository $livreurRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }
        $commande = new Commande();
        $commande->setUser($user);

        $premierLivreurDisponible = $livreurRepository->createQueryBuilder('l')
            ->where('l.disponibilite = :disponibilite')
            ->setParameter('disponibilite', 'disponible')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $commande->setLivreur($premierLivreurDisponible);
        $commande->setStatut("en attente");
        $commande->setPrixtotale($panierRepository->find($user->getId())->getPrixTot());

        $entityManager->createQueryBuilder()
            ->update('App\Entity\Livreur', 'l')
            ->set('l.disponibilite', ':nouveauStatut')
            ->where('l.disponibilite = :disponibilite')
            ->setParameter('nouveauStatut', 'indisponible')
            ->setParameter('disponibilite', 'disponible')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
        
            $entityManager->persist($commande);
            $entityManager->flush();



            return $this->redirectToRoute('app_commande_showfront', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);


    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No ID found');
        }
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/showfront/{id}', name: 'app_commande_showfront', methods: ['GET'])]
    public function showfront(Commande $commande): Response
    {
        return $this->render('commande/showfront.html.twig', [
            'commande' => $commande,
        ]);
    }
    
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/commandes/pdf', name: 'app_commande_pdf')]
    public function pdf(CommandeRepository $commandeRepository)
    {
        // Récupérez les commandes à partir de la base de données
    $commandes = $commandeRepository->findAll();

        // Créez un nouveau document PDF
        $pdf = new FPDF();

        // Ajoutez une page en mode paysage
        $pdf->AddPage('L');
        // Définissez la police et la taille du texte
        $pdf->SetFont('Arial', 'B', 16);

        // Ajoutez un titre
        $pdf->Cell(0, 10, 'Liste des commandes', 0, 1, 'C');

        // Ajoutez les en-têtes de colonne
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 10, 'ID', 1, 0);
        $pdf->Cell(30, 10, 'Statut', 1, 0);
        $pdf->Cell(60, 10, 'Livreur', 1, 0);
        $pdf->Cell(30, 10, 'User_ID', 1, 1);
        // Ajoutez les données des commandes
        $pdf->SetFont('Arial', '', 12);
        foreach ($commandes as $commande) {
            // Vérifiez que la commande a bien un utilisateur et un livreur associés
            if ($commande->getUser() && $commande->getLivreur()) {
                $pdf->Cell(30, 10, $commande->getId(), 1, 0);
                $pdf->Cell(30, 10, $commande->getStatut(), 1, 0);
                $pdf->Cell(60, 10, $commande->getLivreur()->getNom(), 1, 0);
                $pdf->Cell(30, 10, $commande->getUser()->getID(), 1, 1);
            }
        }
        $pdf->Output('F', '../public/Front/commandes.pdf');

        $request = $this->requestStack->getCurrentRequest();
        $response = $this->uploadPdf($request);

        return $this->redirectToRoute('app_commande_index');
        
    }

    #[Route('/upload-pdf', name: 'upload_pdf')]
    public function uploadPdf(Request $request): Response
    {
        // Chemin du fichier PDF préalablement généré
        $pdfFilePath = '../public/Front/commandes.pdf';
    
        // Vérifiez que le fichier existe
        if (file_exists($pdfFilePath)) {
            // Enregistrez le fichier dans la base de données (utilisez Doctrine pour cela)
            $pdfFile = new PdfFile(); // Utilisez le nom correct de l'entité PdfFile
            $pdfFile->setFileName('commandes.pdf');
            $pdfFile->setFileData(file_get_contents($pdfFilePath)); // Utilisez file_get_contents pour lire les données du fichier
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pdfFile);
            $entityManager->flush();
    
            return new Response('Fichier PDF enregistré avec succès!');
        }
    
        return new Response('Le fichier PDF n\'existe pas.', 400);
    }
    
    
}
