<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class PasswordHashSubscriber implements EventSubscriberInterface
{
     /** @var UserPasswordInterface */
    
     private $encoder;

     

     public function __construct(UserPasswordEncoderInterface $encoder)
     {
         
         $this->encoder = $encoder;
     }
 
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE]
        ];
    }
    
    public function hashPassword(ViewEvent $event)
    {
        $user =   $event->getControllerResult();
        
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || !in_array($method, [Request::METHOD_POST])) {
        return;
    }
    var_dump($user->getPassword());
        $user->setPassword(
            $this->encoder->encodePassword($user, $user->getPassword())
        );
        
    }
}