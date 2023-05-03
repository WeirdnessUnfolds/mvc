<?php

namespace App\Card;

use App\Controller;

/*
A class that represents a card.
*/

class DeckOfCardsJoker extends DeckOfCards
{   /* Represents the color and value of the card, random
    number between 1 and 53. */

    public function addJoker()
    {
        $card = new Card(53);
        array_push($this->cards, $card);

    }












}
