<?php

namespace App\Controller;
use App\Card\Card;
use App\Card\DeckOfCards;
use App\Card\DeckofCardsJoker;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardControllerTwig extends AbstractController
{



    #[Route("/card", name: "card_landing")]
    public function apisum() : Response
    {
        return $this->render('card.html.twig');
    }
    
    #[Route("/card/shuffle", name: "card_shuffle")]
    public function shufflecards() : Response
    {
        $cardDeck = new deckOfcards();
        $cardDeck->shuffleCards();

        $data = [
            "cardView" => $cardDeck->getDisplay(),

        ];

        return $this->render('card_shuffle.html.twig', $data);
    }
    



}
