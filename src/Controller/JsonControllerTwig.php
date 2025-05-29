<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class JsonControllerTwig extends AbstractController
{
    #[Route("/api", name: "api_landing")]
    public function apisum(): Response
    {
        return $this->render('apilanding.html.twig');
    }

    #[Route("/api/deck", name:"api_deck", methods:['GET'])]
    public function apideck(
        SessionInterface $session
    ): Response {

        // If the deck exists already..
        $deck = $session->get("active_deck");

        if (!$deck) {
            $deck = new DeckOfCards();
            $session->set("active_deck", $deck);
        }
        $deck = $session->get("active_deck");
        $data = [
            "deckView" => $deck->getDisplay(),

        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;


    }

    #[Route("/api/game", name:"game_stats", methods:['GET'])]
    public function gameStats(
        SessionInterface $session
    ): Response {
        $game = $session->get("active_deck");
        $player = $session->get("player");
        $cpu = $session->get("cpu");
        $winner = $session->get("winner");
        if (!$game) {
            throw new Exception(("Det finns inget aktivt spel!"));
        }


        $data = [
            "playerpoints" => $player->getPoints(),
            "cpupoints" => $cpu->getPoints(),
            "winner" => $winner,

        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("api/deck/shuffle", name:"api_shuffle", methods:['POST'])]
    public function apishuffle(
        SessionInterface $session
    ): Response {
        $deck = $session->get("active_deck");
        $deck->shuffleCards();
        $session->set("active_deck", $deck);
        $data = [
            "deckView" => $deck->getDisplay(),

        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;




    }

    #[Route("/api/deck/draw", name: "card_drawapi", methods: ['POST'])]
    public function drawAPI(
        SessionInterface $session
    ): Response {
        $cardDeck = $session->get("active_deck");
        if (count($cardDeck->getCards()) < 1) {
            throw new Exception(("Du kan inte ta upp flera kort än det finns i leken!"));
        }

        $data = [
            "handView" => $cardDeck->drawCard(1),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];

        $session->set("active_deck", $cardDeck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;


    }


    #[Route("/api/deck/draw/{num<\d+>}", name: "card_drawseveralapi", methods: ['POST'])]
    public function drawNum(
        int $num,
        SessionInterface $session
    ): Response {
        $cardDeck = $session->get("active_deck");
        if ($num > 52 or $num > count($cardDeck->getCards())) {
            throw new Exception(("Du kan inte ta upp flera kort än det finns i leken!"));
        }

        $data = [
            "handView" => $cardDeck->drawCard($num),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];

        $session->set("active_deck", $cardDeck);


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;


    }






}
