<?php

namespace App\Email;

use Swift_Message;
use App\Entity\User;
use Twig\Environment;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \TwigEnvironment
     */
    private $twig;

    public function __construct(
        \Swift_Mailer $mailer, \Twig\Environment $twig
    )
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render(
            'email/confirmation.html.twig',
            [
                'user' => $user
            ]
        );

        $message = (new Swift_Message('Please confirm your account!'))
            ->setFrom('diallomouctar7200@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}