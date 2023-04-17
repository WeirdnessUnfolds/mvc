<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JsonControllerTwig extends AbstractController
{

    #[Route("/api", name: "api_landing")]
    public function apisum() : Response
    {
        return $this->render('apilanding.html.twig');
    }

}
