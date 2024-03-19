<?php

namespace App\Form;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    private $mailer;

    public function  __construct(Mailerinterface $mailer) 
    {
        $this->mailer = $mailer;
    }
    public function sendEmail()
    {
       $email = (new Email())
       ->from('hello@exemple.com')
       ->to('you@exemple.com')
       //->cc('cc@exemple.com')
    //
    //
    //->pcntl_getpriority
    ->subject('Hello world')
    ->text('Sending emails is fun again !')
    ->html('<p>42</p>');

    $this->mailer->send($email);
     }
}

