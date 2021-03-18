<?php

namespace App\EventSubscriber;

use App\Exception\EmptyBodyException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmptyBodySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleEmptyBody', EventPriorities::POST_DESERIALIZE],
        ];
    }

    public function handleEmptyBody(ViewEvent $event)
    {
        $method = $event->getRequest()->getMethod();

        if (!in_array($method, [Request::METHOD_POST, Request::METHOD_PUT])) {
          return;  
        }

        $data = $event->getRequest()->get('data');

       if(null === $data) {
           throw new EmptyBodyException();
       }
    }
}
  