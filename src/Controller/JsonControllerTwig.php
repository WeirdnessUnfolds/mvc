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
    public function apisum() : Response
    {
        return $this->render('apilanding.html.twig');
    }

    #[Route("/api/deck", name:"api_deck", methods:['GET'])]
    public function apideck(
        SessionInterface $session
    ) : Response 
    {
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
    ) : Response 
    {
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


}
