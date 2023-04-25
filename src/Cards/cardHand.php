<?php 

namespace App\Card;
use App\Controller;
use App\Card\Card;
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

    public function viewHand()
    {
        foreach ($this->cardsInhand as $currcard){
            array_push($this->graphicarray, $currcard->getAsGraphic());
        }
        return $this->graphicarray;
    }
}