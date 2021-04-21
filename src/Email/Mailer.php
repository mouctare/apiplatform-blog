<?php

namespace App\Email;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class Mailer {
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(array $arguments): void
    {
        ['recipient_email' => $recipientEmail, 'subject' => $subject, 'html_template' => $htmlTemplate,'context' => $context ] = $arguments;

        $email = new TemplatedEmail();
        $email->from('diallomouctar7200@gmail.com')
              ->to($recipientEmail)
              ->subject($subject)
              ->htmlTemplate($htmlTemplate)
              ->context($context);

              try {
                   $this->mailer->send($email);
              } catch(TransportExceptionInterface $mailerException){
                  throw $mailerException;
              }
    }
}