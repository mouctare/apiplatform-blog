<?php

namespace App\Doctrine\Listener;

use App\Entity\Post;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostSlugListener
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
       $this->slugger = $slugger;
    }
    public function prePersist(LifecycleEventArgs $event)
    {
          $entity = $event->getObject();
          if(!$entity instanceof Post){
              return;
          }
          if(empty($entity->getSlug())) {
              $entity->setSlug(strtolower($this->slugger->slug($entity->getTitle())));
          }
    }
}