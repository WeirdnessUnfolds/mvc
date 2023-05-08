<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $deck = new DeckOfCards();

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

    #[Route("api/deck/shuffle", name:"api_shuffle", methods:['POST'])]
    public function apishuffle(
        SessionInterface $session
    ): Response {
        $session->clear();
        $deck = new deckOfcards();
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
        if (count($cardDeck->getCards()) < 0) {
            throw new \Exception(("Du kan inte ta upp flera kort än det finns i leken!"));
        }

        $data = [
            "handView" => $cardDeck->drawCard(1),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];



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
        $activedeck = $session->get("active_deck");
        if ($num > 52 or $num > count($activedeck->getCards())) {
            throw new \Exception(("Du kan inte ta upp flera kort än det finns i leken!"));
        }
        $cardDeck = $session->get("active_deck");


        $data = [
            "handView" => $cardDeck->drawCard($num),
            "cardsLeft" => $cardDeck->getcardsLeft(),
        ];


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;


    }





}
