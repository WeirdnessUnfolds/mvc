<?php

namespace App\Controller;
use App\Card\Card;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardControllerTwig extends AbstractController
{



    #[Route("/card", name: "card_landing")]
    public function apisum() : Response
    {
        $card = new Card();

        $data = [
            "cardView" => $card->getAsGraphic(),
        ];

        return $this->render('card.html.twig', $data);
    }
    


}
