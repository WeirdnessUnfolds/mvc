<?php

namespace App\Card;

use App\Controller;

/*
A class that represents a card.
*/

class DeckOfCardsJoker extends DeckOfCards
{   /* Represents the color and value of the card, random
    number between 1 and 53. */

    public function __construct()
    {

        $this->cards = array();
        $this->cardDisplay = array();
        $this->cardDisplayAPI = array();

        for ($i = 1; $i <= 53; $i++) {
            $card = new Card($i);
            array_push($this->cards, $card);
        }

        $this->setDisplay();

    }









}
