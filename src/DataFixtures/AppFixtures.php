<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        
    }
    public function load(ObjectManager $manager)
    {
        $users = [];
        for($i = 1; $i <= 10; $i++){
             $user = new User();
             $user->setPassword($this->userPasswordEncoder->encodePassword($user, "password"));
             $user->setEmail(sprintf("test+%d@yahoo.fr", $i));
             $user->setName(sprintf("name+%d", $i));
            $manager->persist($user);

            $users[] = $user;
        }

        
          foreach ($users as $user){
            for ($j = 1; $j <= 5; $j++){
                $post =  Post::create("Contenu du post", $user);

                shuffle($users);
                
                foreach (array_slice($users, 5) as $userCanLike){
                    $post->likedBy($userCanLike);
                }
                $manager->persist($post);

                for ($k = 1; $k <= 10; $k++) {
                    $comment  = Comment::create (sprintf("j'aime bien ce post %d", $k), $users[array_rand($users)], $post);
                    $manager->persist($comment);

                }
              
              }

          
        }
        $manager->flush();

     
    }
}
