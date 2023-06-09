<?php

namespace App\Card;

use App\Controller;

/*
A class that represents a card.
*/

class DeckOfCards
{   /* Represents the color and value of the card, random
    number between 1 and 53. */
    protected $cards;
    protected $cardDisplay = array();
    protected $cardDisplayAPI = array(); // For the API display

    public function __construct()
    {

        $this->cards = array();
        $this->cardDisplay = array();
        $this->cardDisplayAPI = array();

        for ($i = 1; $i <= 52; $i++) {
            $card = new Card($i);
            array_push($this->cards, $card);
        }

    }

    public function getDisplayAPI()
    {
        foreach ($this->cards as $currcard) {
            array_push($this->cardDisplayAPI, $currcard);
        }
        return $this->cardDisplayAPI;
    }
    public function getDisplay()
    {
        foreach ($this->cards as $currcard) {
            $this->cardDisplay[$currcard->getAsGraphic()]["Color: "] = $currcard->getColor();
        }
        return $this->cardDisplay;


    }

    public function shuffleCards()
    {

        shuffle($this->cards);


    }

    public function getCards()
    {
        return $this->cards;
    }

    public function drawCard($numofCards = null)
    {
        $heldCards = array();

        for($i = 0; $i <= ($numofCards-1); $i++) {
            array_push($heldCards, array_pop($this->cards));

        }

        $hand = new cardHand($heldCards);

        return $hand->viewHand();


    }

    public function getcardsLeft()
    {
        return count($this->cards);
    }









}
