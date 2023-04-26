<?php

namespace App\Controller;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\DeckofCardsJoker;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardControllerTwig extends AbstractController
{



    #[Route("/card", name: "card_landing")]
    public function apisum() : Response
    {
        return $this->render('card.html.twig');
    }
    



    #[Route("/card/deck/shuffle", name: "card_shuffle", methods:['POST', 'GET'])]
    public function initCallBack(
        SessionInterface $session
    ) : Response
    {   
        $session->clear();
        $playingDeck = new deckOfcards();
        $session->set("active_deck", $playingDeck);
        $deckColors = array();
        $playingDeck->shuffleCards();
        
      
        for ($i = 0; $i < 51; $i++ )
        {
            $currcard = $playingDeck->getCards()[$i];
            array_push($deckColors, ($currcard->getColor()));
        }
        $session->clear();
        $session->set("active_deck", $playingDeck);
    
        $data = [
            "cardView" => $playingDeck->getDisplay(),
            "colors" => $session->get("deck_colors")
        ];
        return $this->render("card_shuffle.html.twig", $data);
    }

    #[Route("/card/deck/draw", name: "card_draw")]
    public function draw(
        SessionInterface $session
    ) : Response
    {
        $cardDeck = $session->get("active_deck");


        $data = [
            "handView" => $cardDeck->drawCard(1),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];

        return $this->render('card_draw.html.twig', $data);
    }
    

    #[Route("/card/deck/draw/{num<\d+>}", name: "card_draw_num", methods: ['GET'])]
    public function drawNum(int $num,
    SessionInterface $session) : Response
    {   
        $deckColors = array();
        $activedeck = $session->get("active_deck");
        if ($num > 52 or $num > count($activedeck->getCards())) {
            throw new \Exception(("Du kan inte ta upp flera kort än det finns i leken!"));
        }
        $cardDeck = $session->get("active_deck");


        $data = [
            "handView" => $cardDeck->drawCard($num),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];

        return $this->render('card_draw.html.twig', $data);
    }

    



}
