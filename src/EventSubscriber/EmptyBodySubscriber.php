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
        $request = $event->getRequest();
        $method = $request->getMethod();
        $route = $request->get('_route');

        if (!in_array($method, [Request::METHOD_POST, Request::METHOD_PUT]) ||
            in_array($request->getContentType(), ['html', 'form']) ||
            substr($route, 0, 3) !== 'api') {
            return; 
        }

        $data = $event->getRequest()->get('data');

       if(null === $data) {
           throw new EmptyBodyException();
       }
    }
}
  