<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ResetPasswordAction
{
    /**
     * @var ValidatorInterface
    */
    private $validator;

  /** @var UserPasswordInterface */
    
    private $encoder;

     /**@var EntityMangerInterface   */

      private $manager;

    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;
 
    public function __construct(ValidatorInterface $validator, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager, JWTTokenManagerInterface $tokenManager)
    {
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->manager = $manager;
        $this->tokenManager = $tokenManager;
    }
    public function __invoke(User $data)
    {
      // $reset = new ResetPasswordAction
      // $rest(); 
      // Ici la variable data réprensente la class User ensuite le dump ne marchait pas puisque postaman ne savais pas quel proprité retouré d'ou la précision des propriété
     // var_dump($data->getNewPassword(), $data->getNewRetypedPassword(),  $data->getOldPassword(),  $data->getRetypedPassword()
   //);
   // die;

    // Validator is only called after we return the data from this action! Le validateur n'est appelé qu'après le retour des données de cette action !

    $this->validator->validate($data);

    $data->setPassword($this->encoder->encodePassword($data, $data->getNewPassword()));

    //return $data;

    // After password change old tokens are still valid  Après le changement de mot de passe, les anciens tokens sont toujours valides. donc on les mets une date d'expiration

    $data->setPasswordChangeDate(time());


    $this->manager->flush();

    $token = $this->tokenManager->create($data);

    return new JsonResponse(['token' => $token]);

    // Only hear it checks for user current password, but we've just modified it!  Il ne vérifie que le mot de passe actuel de l'utilisateur, mais nous venons de le modifier !

    // Entity is persisted automatical, only if validation pass  L'entité est persistée automatiquement, seulement si la validation passe.
    }
}