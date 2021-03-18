<?php

namespace App\EventSubscriber;
use App\Entity\Post;

use App\Entity\Comment;
use App\Entity\AuthoredEntityInterface;
use Symfony\Component\HttpKernel\Event;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AuthoredEntitySubscriber implements EventSubscriberInterface 
{
    private $security;
   
    public function __construct(Security $security)
    {
        $this->security = $security;
       

    }
    public static function getSubscribedEvents()
    {

        return [
            KernelEvents:: VIEW => ['getAuthenticatedUser', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function getAuthenticatedUser(ViewEvent $event) {
        $entity = $event->getControllerResult();
        
        $method = $event->getRequest()->getMethod();
        
        if (!$entity instanceof AuthoredEntityInterface ||  $method !== 'POST') {
            return;
        }

        $user = $this->security->getUser();
        
        $entity->setAuthor($user);
        }

        }
        
    
        
        