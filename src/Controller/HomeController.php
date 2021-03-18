<?php

namespace App\Controller;

use App\Security\UserConfirmationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="default_index")
     */
    public function index()
    {
      return $this->render('base.html.twig');
    }

   /**
   * @Route("/confirm-user/{token}", name="default_confirm_token")
   */
  public function ConfirmUser(string $token, UserConfirmationService $userConfirmationService)
  {
    $userConfirmationService->confirmUser($token);

    return $this->redirectToRoute('default_index');
  }
}
