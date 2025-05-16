<?php

namespace App\Card;

use App\Controller;
use App\Card\Card;
use App\Card\Player;
use App\Card\DeckOfCards;
use App\Card\DeckOfCardsJoker;

/*
A class that represents a hand of cards that draws cards from a cardHand.
*/

class CardHand
{   /* Displays the cards that have been drawn from a deck in a hand. */
    private $cardsInhand;
    private $graphicarray = array();


    public function __construct($drawnCards)
    {

        $this->cardsInhand = $drawnCards;
    }

    public function viewHand(): array
    {
        foreach ($this->cardsInhand as $currcard) {
            $this->graphicarray[$currcard->getAsGraphic()]["Color: "] = $currcard->getColor();
        }
        return $this->graphicarray;
    }

    public function addCard($card)
    {
        $this->cardsInhand[] = $card;
    }

    public function getPoints()
{
    $totalpoints = 0;
    $cardsInhand = $this->cardsInhand;
    foreach ($this->cardsInhand as $currcard) {
        dump($totalpoints);
        $totalpoints += $currcard->getCardPoints();
    }
    return $totalpoints;
}

}
