<?php 

namespace App\Card;
use App\Controller;
use App\Card\Card;

/*
A class that represents a card.
*/

class DeckOfCards
{   /* Represents the color and value of the card, random
    number between 1 and 53. */
    protected $cards;
    protected $cardDisplay;

    public function __construct()
    {  

        $this->cards = array();
        $this->cardDisplay = array();

       for ($i = 1; $i <= 52; $i++)
       {
        $card = new Card($i);
        array_push($this->cards, $card);
       }
        
    }   

    public function getDisplay()
    {
        foreach ($this->cards as $currcard){
            array_push($this->cardDisplay, $currcard->getAsGraphic());
        }
        return $this->cardDisplay;
    }

    public function shuffleCards()
    {   
      
     shuffle($this->cards);


    }


    






}