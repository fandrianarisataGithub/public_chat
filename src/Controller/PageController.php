<?php

namespace App\Controller;

use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // renvoyer vers le login si pas connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('page/index.html.twig');
    }

}
