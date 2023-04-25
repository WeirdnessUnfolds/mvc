<?php

namespace App\Controller;
use App\Card\Card;
use App\Card\CardHand;
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
    
    #[Route("/card/deck/shuffle", name: "card_shuffle")]
    public function shufflecards() : Response
    {
        $cardDeck = new deckOfcards();
        $cardDeck->shuffleCards();

        $data = [
            "cardView" => $cardDeck->getDisplay(),

        ];

        return $this->render('card_shuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_draw")]
    public function draw() : Response
    {
        $cardDeck = new deckOfcards();


        $data = [
            "handView" => $cardDeck->drawCard(1),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];

        return $this->render('card_draw.html.twig', $data);
    }
    

    #[Route("/card/deck/draw/{num<\d+>}", name: "card_draw_num")]
    public function drawNum(int $num) : Response
    {
        if ($num > 52) {
            throw new \Exception(("Du kan inte ta upp flera kort än det finns i leken!"));
        }
        $cardDeck = new deckOfcards();


        $data = [
            "handView" => $cardDeck->drawCard($num),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];

        return $this->render('card_draw.html.twig', $data);
    }
    



}
