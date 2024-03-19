<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    
    public function index(Request $request, Mailer $mailer): Response
    {
        $form=$this->createForm(ContactType::class);
        $form->handleRequest($_REQUEST);

        if($form->isSubmitted()&& $form->isValid()) {
            $data=$form->getData();
            $email=$data['email'];
            $mailer->sendEmail();
        
        
        return $this->render('contact/succes.html.twig' , [
'email' => $email
        ]);
    }else{
return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
    }
}