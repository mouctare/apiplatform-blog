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
    public function prePersist(Post $entity, LifecycleEventArgs $event)
    {
        //  $entity = $event->getObject();
         // if(!$entity instanceof Post){
          //    return; pour des soucis de performance , il faut mettre ainsi ceci veut dire qu'on appelle l'evenement que quand il s'agit d'un Post
         // }
          if(empty($entity->getSlug())) {
              $entity->setSlug(strtolower($this->slugger->slug($entity->getTitle())));
          }
    }
}