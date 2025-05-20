<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\DeckOfCardsJoker;
use App\Card\Player;
use App\Card\CpuPlayer;
use App\Card\Game;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
            if ($value instanceof DeckOfCards) {
                $sessionData[$key] = $value->getDisplayAPI();
            } elseif ($value instanceof Player) {
                $sessionData[$key] = [
                    'hand' => $value->getHand(),
                    'points' => $value->getPoints(),
                    'name' => $value->getName(),
                ];
            } elseif ($value instanceof Game) {
                $sessionData[$key] = [
                    'player' => $value->player->getHand()->viewHand(),
                    'cpuPlayer' => $value->cpuPlayer->getHand()->viewHand(),
                    'deck' => $value->deck->getDisplayAPI(),
                ];
            }
            // Needed to decode the utf8 characters in the session data
            if (is_array($sessionData[$key])) {
                foreach ($sessionData[$key] as &$card) {
                    if (is_array($card) && isset($card['graphic'])) {
                        $card['graphic'] = json_decode('"' . $card['graphic'] . '"');
                    }
                }
            }
        }


        return $this->render('session_view.html.twig', [
            'sessionData' => $sessionData,
        ]);
    }

    #[Route("/game_landing", name: "game_landing", methods: ['GET'])]
    public function gameLanding(): Response
    {


        return $this->render('game_landing.html.twig');
    }

    #[Route("/gamedoc", name: "gamedoc", methods: ['GET'])]
    public function gameDoc(): Response
    {
        return $this->render('gamedoc.html.twig');
    }

    #[Route("/gameview", name: "gameview", methods: ['GET', 'POST'])]
    public function gameView(
        SessionInterface $session,
        Request $request
    ): Response {
        $deck = $session->get("active_deck");
        $player = $session->get("player");
        $game = $session->get("game");
        $cpu = $session->get("cpu");
        $isFirstTurn = $session->get("is_first_turn", true);
        $playerAction = "first_turn";

        if ($request->get('action') == "reset") {
            $session->clear();
            // Session needs to be an instance of a Symfony session.
            if ($session instanceof \Symfony\Component\HttpFoundation\Session\Session) {
                $session->getFlashBag()->clear();
                }
            $this->addFlash('success', 'Sessionen har blivit rensad, du kan nu spela igen.');
            return $this->render('game_landing.html.twig');
        }

        if (!$deck) {
            $deck = new DeckOfCards();
            $session->set("active_deck", $deck);


        }

        if (!$player) {
            $player = new Player("Player");
            $session->set("player", $player);

        } else {
            $player = $session->get("player");
            $deck = $session->get("active_deck");
        }

        if (!$cpu) {
            $cpu = new CpuPlayer();
            $session->set("cpu", $cpu);

        } else {
            $cpu = $session->get("cpu");
            $deck = $session->get("active_deck");

        }

        if (!$game) {
            $game = new Game($player, $cpu, $deck);
            $session->set("game", $game);
        } else {
            $game = $session->get("game");
        }



        $session->set("cards_left", $deck->getcardsLeft());
        $session->set("player", $player);
        $session->set("cpu", $cpu);

        $winner = null; // No winner yet

        if ($isFirstTurn) {
            $playerAction = "first_turn";
            $session->set("is_first_turn", false);
        } else {
            $playerAction = $request->request->get('action');


        }
        $winner = $game->playGame($playerAction);
        $session->set("player", $game->player);
        $session->set("cpu", $game->cpuPlayer);
        $session->set("active_deck", $game->deck);
        $session->set("game", $game);
        $session->set("winner", $winner);

        $data = [
            "player_handView" => $game->player->viewHand(),
            "points" => $game->player->getPoints(),
            "cpu_handView" => $game->cpuPlayer->viewHand(),
            "cpupoints" => $game->cpuPlayer->getPoints(),
            "winner" => $winner,
        ];

        return $this->render('game_view.html.twig', $data);
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
            "points" => $hand->getPoints(),
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
        $drawnCards = $cardDeck->drawCard($num);
        if ($drawnCards == null) {
            throw new \Exception(("Det finns inga kort kvar i leken!"));
        }

        $hand = new cardHand($drawnCards);

        $data = [
            "handView" => $hand->viewHand(),
            "cardsLeft" => $cardDeck->getcardsLeft(),
            "points" => $hand->getPoints(),
        ];

        $session->set("active_deck", $cardDeck);
        $session->set("cards_left", $cardDeck->getcardsLeft());
        return $this->render('card_draw.html.twig', $data);
    }
}
