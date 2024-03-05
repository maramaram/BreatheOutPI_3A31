<?php

namespace App\Controller;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twilio\Rest\Client;
use App\Entity\User;
use App\Form\ChangerPasswordType;
use App\Form\ConfirmCodeType;
use App\Form\ModifyUserType;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/user')]
class UserController extends AbstractController
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;

    }

//ajout frontEnd
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $user = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = new User();
            $password = $user->getPwd();
            $hashedPassword1 = $this->hasher->hashPassword(
                $newUser,
                $password
            );
            $user->setPwd($hashedPassword1);
            $directory = 'Front/images';
            $directoryy = 'C:/Users/bouaz/ThirdProject/public/Front/images';
            // Récupérez le fichier téléchargé à partir du formulaire
            $file = $form->get('photo')->getData();
            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid() . '.' . $file->guessExtension();
            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);
            // Enregistrez le chemin de l'image dans votre base de données
            $user->setPhoto($directory . '/' . $fileName);
            $entityManager->persist($user);
            $entityManager->flush();

            $mail = new \PHPMailer\PHPMailer\PHPMailer(true); // Make sure to have the correct namespace


            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->Username = 'BreatheOutEnergy@outlook.com'; // Your Outlook email address
                $mail->Password = 'Breathe123@'; // Your Outlook password
                // Sender and recipient settings
                $mail->setFrom('BreatheOutEnergy@outlook.com', 'BreatheOut');
                $nomUser = $user->getPrenom(); // Use the $user object
                $emailUser = $user->getEmail(); // Use the $user object
                $mail->addAddress($emailUser);
                $mail->isHTML(true);
                $mail->Subject = 'PRODUCT VERIFIED !';
                $mail->Body = "Dear $nomUser , <br> Welcome to BreatheOut! 🌿

We're delighted you've joined our mindful community. Get ready to unwind, destress, and discover your path to inner peace. Your journey starts here!

Breathe in, breathe out, and enjoy the serenity.

Best,
The BreatheOut Team";
                $mail->AltBody = "hi";
                $mail->send();
                $this->addFlash('success', 'Verification email sent. Please check your inbox.');
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                $this->addFlash('error', 'Mailer Error: ' . $e->getMessage());
                // Log the error message
                // error_log('Mailer Error: ' . $e->getMessage());
            }
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    //suppression de user frontEnd
    #[Route('/{id}', name: 'app_user_back_delete', methods: ['POST'])]
    public function delete_back(Request $request, EntityManagerInterface $entityManager, $id, UserRepository $repository): Response
    {
        $user = $repository->find($id);
        if (!$id) {
            throw $this->createNotFoundException('No ID found');
        }
        if ($user != null) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        $user = $entityManager->getRepository(User::class)->find($id);
        return $this->redirectToRoute('app_user_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }

//affichage de user frontEnd

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

//modification de user frontEnd
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModifyUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $directory = 'Front/images';
            $directoryy = 'C:/Users/bouaz/ThirdProject/public/Front/images';
            // Récupérez le fichier téléchargé à partir du formulaire
            $file = $form->get('photo')->getData();
            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid() . '.' . $file->guessExtension();
            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);
            // Enregistrez le chemin de l'image dans votre base de données
            $user->setPhoto($directory . '/' . $fileName);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

//suppression de user backEnd
    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, $id, UserRepository $repository): Response
    {

        $user = $repository->find($id);
        if (!$id) {
            throw $this->createNotFoundException('No ID found');
        }
        if ($user != null) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        $user = $entityManager->getRepository(User::class)->find($id);
        return $this->redirectToRoute('app_user_login', [], Response::HTTP_SEE_OTHER);
    }


    //affichage list user Backend
    #[Route('/back/{id}', name: 'app_user_index', methods: ['GET'])]
    public function index($id,SessionInterface $session, FlashyNotifier $flashy,Request $request, UserRepository $userRepository): Response
    {
        $isNewUserAdded = $session->get('isNewUserAdded', false);
        if ($isNewUserAdded) {
            $notificationLink = 'a new user is added';
            $flashy->success('A new user is added', $notificationLink);
            $session->set('isNewUserAdded', false);
        }
        // Récupérez vos données à paginer depuis la base de données
        $queryBuilder = $userRepository->createQueryBuilder('u');

        // Créez un adaptateur Pagerfanta pour vos données
        $adapter = new QueryAdapter($queryBuilder);

        // Créez l'objet Pagerfanta
        $pagerfanta = new Pagerfanta($adapter);

        // Définissez le nombre d'éléments par page
        $pagerfanta->setMaxPerPage(5);

        // Récupérez le numéro de page à partir de la requête (par exemple, via la query string)
        $currentPage = $request->query->get('page', 1);

        // Définissez la page actuelle
        $pagerfanta->setCurrentPage($currentPage);

        // Récupérez les utilisateurs de la page actuelle
        $users = $pagerfanta->getCurrentPageResults();

        // ...

        return $this->render('user/index.html.twig', [

            'users' => $users,
            'pagerfanta' => $pagerfanta, // Make sure to pass the pagerfanta variable to the template

        ]);
    }

//ajout backEnd
    #[Route('/new/back', name: 'app_user_back_new', methods: ['GET', 'POST'])]
    public function new_back(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $user = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = new User();
            $password = $user->getPwd();
            $hashedPassword1 = $this->hasher->hashPassword(
                $newUser,
                $password,
            );
            $user->setPwd($hashedPassword1);
            $directory = 'Front/images';
            $directoryy = 'C:/Users/bouaz/ThirdProject/public/Front/images';
            // Récupérez le fichier téléchargé à partir du formulaire
            $file = $form->get('photo')->getData();
            // Générez un nom unique pour le fichier téléchargé
            $fileName = uniqid() . '.' . $file->guessExtension();
            // Déplacez le fichier vers le répertoire de destination
            $file->move($directoryy, $fileName);
            // Enregistrez le chemin de l'image dans votre base de données
            $user->setPhoto($directory . '/' . $fileName);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('user/new_back.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    //affichage de user Backend
    #[Route('/{id}/b', name: 'app_user_back_show', methods: ['GET'])]
    public function show_back(User $user): Response
    {
        return $this->render('user/show_back.html.twig', [
            'user' => $user,
        ]);
    }

    //modification de user Backend
    #[Route('/{id}/editt', name: 'app_user_back_edit', methods: ['GET', 'POST'])]
    public function edit_back(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModifyUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit_back.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
//forgot password user on login page
    #[Route('/change/password', name: 'forgot')]
    public function forgotPassword(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $num_tel = $form->get('num_tel')->getData();
            $user = $userRepository->findOneBy(['num_tel' => $num_tel]);
            if ($user) {
                $resetCode = sprintf('%04d', mt_rand(0, 9999));
                // Twilio configuration
                $sid    = "AC826dd4583d21e1d761edfdbefca0daf8";
                $token  = "6dfb8cfec4006bd5b1df833c60c9eca2";
                $twilio = new Client($sid, $token);

                // Construct the URL with route parameters

                // Include the URL in the body of the SMS message
                $message = $twilio->messages
                    ->create(
                        "+21655614560",
                        [
                            "from" => "+14845529358",
                            "body" => "Hi " . " " . $user->getNom() ." " .  $user->getPrenom() . " ". "This is your code to reset password : " . $resetCode
                        ]
                    );

                return $this->redirectToRoute('password_code_confirm', ['idC' => $user->getId(), 'code' => $resetCode]);
            } else {
                $this->addFlash('error', 'Phone number does not exist');
                return $this->redirectToRoute('forgot');
            }
        }
        return $this->render('user/user_forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idC}/{code}', name: 'password_code_confirm')]
    public function password_code(UserRepository $repository , $idC , $code ,Request $request): Response
    {

        $newUser = $repository->find($idC);
        $form = $this->createForm(ConfirmCodeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $code1 = $user['code'];
            if($code1 == $code)
            {
                return $this->redirectToRoute('user_change_password', ['idC' => $newUser->getId()]);
            }
            else{
                $this->addFlash('error', 'Invalid code');
            }
        }else{
        return $this->render('user/user_forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);}
        return $this->render('user/code_confirm_check.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('{idC}', name: 'user_change_password')]
    public function change_password(Request $request, UserRepository $repository, $idC, EntityManagerInterface $entityManager): Response
    {
        $newUser = $repository->find($idC);
        $form = $this->createForm(ChangerPasswordType::class,$newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $user->getPwd();
            $hashedPassword1 = $this->hasher->hashPassword(
                $newUser,
                $password
            );
            $newUser->setPwd($hashedPassword1);
            $entityManager->persist($newUser);
            $entityManager->flush();
            $this->addFlash('success', 'Password changed successfully!');
            return $this->redirectToRoute('app_login');
        }else{
            $this->addFlash('ERROR', 'Password DIDNt change');
        }
        $this->addFlash('ERROR', 'Password DIDNt change');
        return $this->render('user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    //suppression de user Backend
    /*  #[Route('/{id}', name: 'app_user_back_delete', methods: ['POST'])]
       public function deleteBack(Request $request, User $user, EntityManagerInterface $entityManager): Response
       {
           if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
               $entityManager->remove($user);
               $entityManager->flush();
           }

           return $this->redirectToRoute('app_user_back_index', [], Response::HTTP_SEE_OTHER);
       }*/

    // user inactive admin
    #[Route('/inactive/user/{idActive}', name: 'user_inactive')]
    public function user_active(UserRepository $repository,EntityManagerInterface $entityManager, $idActive): Response
    {
        $userActive = $repository->find($idActive);
        if (!$userActive) {
            throw $this->createNotFoundException('No user found');
        }
        $userActive->setStatus("active");
        $entityManager->flush();
        return $this->redirectToRoute('app_user_index', ['id' => $idActive], Response::HTTP_SEE_OTHER);
    }

}
