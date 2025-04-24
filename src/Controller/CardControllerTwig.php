<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\DeckofCardsJoker;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardControllerTwig extends AbstractController
{
    #[Route("§/card", name: "card_landing")]
    public function apisum(): Response
    {
        return $this->render('card.html.twig');
    }


    #[Route("/session", name: "session", methods: ['GET'])]
    public function printSession(SessionInterface $session): Response
    {

        $sessionData = $session->all();

        
    foreach ($sessionData as $key => $value) {
        if ($value instanceof DeckOfCards || $value instanceof DeckofCardsJoker) {
            $sessionData[$key] = $value->getDisplayAPI();
        }
    }

    // Needed to decode the utf8 characters in the session data
    if (is_array($sessionData[$key])) {
            foreach ($sessionData[$key] as &$card) {
                if (isset($card['graphic'])) {
                    $card['graphic'] = json_decode('"' . $card['graphic'] . '"');
                }
            }
        }

    
        return $this->render('session_view.html.twig', [
            'sessionData' => $sessionData,
        ]);
    }

    
    #[Route("/session_clear", name: "session_clear", methods: ['POST'])]
    public function clearSession(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash('success', 'Sessionen har blivit rensad.');
        return $this->render('card.html.twig');
    }



    #[Route("/card/deck", name: "card_getdeck", methods: ['POST', 'GET'])]
    public function getDeck(
        SessionInterface $session
    ): Response {
        // If the deck exists already..
        $deck = $session->get("active_deck"); 

        if (!$deck) {
            $deck = new DeckOfCards();
            $session->set("active_deck", $deck);
            $session->set("cards_left", $deck->getcardsLeft());
        } else {
            $deck = $session->get("active_deck");
            $deck->sortCards();
        }

        $data = [
            "deckView" => $deck->getDisplay(),

        ];
        return $this->render("card_deckview.html.twig", $data);
    }

    #[Route("/card/deckjoker", name: "card_getdeckjoker", methods: ['POST', 'GET'])] // Because there should be no parameter in the "normal" card/deck..
    public function getDeckJoker(
        SessionInterface $session
    ): Response {

        $deck = $session->get("active_deck"); 
        if (!$deck) {
            $deck = new DeckOfCardsJoker();
            $session->set("active_deck", $deck);
            $session->set("cards_left", $deck->getcardsLeft());
        } else {
        $deck = $session->get("active_deck");
        }
        $data = [
            "deckView" => $deck->getDisplay(),

        ];

        return $this->render("card_deckview.html.twig", $data);
    }





    #[Route("/card/deck/shuffle", name: "card_shuffle", methods: ['POST', 'GET'])]
    public function initCallBack(
        SessionInterface $session
    ): Response {
        $playingDeck = $session->get("active_deck");
        $playingDeck->shuffleCards();
        $session->set("active_deck", $playingDeck);

        $data = [
            "cardView" => $playingDeck->getDisplay()
        ];
        return $this->render("card_shuffle.html.twig", $data);
    }




    #[Route("/card/deck/draw", name: "card_draw")]
    public function draw(
        SessionInterface $session
    ): Response {
        $cardDeck = $session->get("active_deck");
        $drawnCards = $cardDeck->drawCard(1);
        $hand = new cardHand($drawnCards);

        $session->set("active_deck", $cardDeck);

        $data = [
            "handView" => $hand->viewHand(),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];

        $session->set("cards_left", $cardDeck->getcardsLeft());
        return $this->render('card_draw.html.twig', $data);
    }


    #[Route("/card/deck/draw/{num<\d+>}", name: "card_draw_num", methods: ['GET'])]
    public function drawNum(
        int $num,
        SessionInterface $session
    ): Response {
        $activedeck = $session->get("active_deck");
        if ($num > 52 or $num > count($activedeck->getCards())) {
            throw new \Exception(("Du kan inte ta upp flera kort än det finns i leken!"));
        }
        $cardDeck = $session->get("active_deck");


        $data = [
            "handView" => $cardDeck->drawCard($num),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];

        $session->set("active_deck", $cardDeck);
        $session->set("cards_left", $cardDeck->getcardsLeft());
        return $this->render('card_draw.html.twig', $data);
    }
}

