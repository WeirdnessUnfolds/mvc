<?php

namespace App\Card;

use App\Controller;

/*
A class that represents a card.
*/

class DeckOfCards
{   /* Represents the color and value of the card, random
    number between 1 and 53. */
    /** @var Card[] */
    protected array $cards;
    /** @var array<string, array<string, string>> */
    protected array $cardDisplay = array();
    /** @var array<int, array<string, string|int>> */
    protected array $cardDisplayAPI = array(); // For the API display

    public function __construct()
    {

        $this->cards = array();
        $this->cardDisplay = array();
        $this->cardDisplayAPI = array();

        for ($i = 1; $i <= 52; $i++) {
            $card = new Card($i);
            array_push($this->cards, $card);
        }


        $this->setDisplay();
    }
    /**
    * @return array<int,  array<string, string|int>> The display of the cards in the deck for API.
    * Each card is represented by its graphic, color, and number.
    */
    public function getDisplayAPI(): array
    {
        $this->cardDisplayAPI = []; // Reset the array to avoid duplication
        foreach ($this->cards as $currcard) {
            $this->cardDisplayAPI[] = [
                'graphic' => $currcard->getAsGraphic(),
                'color' => $currcard->getColor(),
                'number' => $currcard->getcardNumrep(),];
        }
        return json_decode(json_encode($this->cardDisplayAPI, JSON_UNESCAPED_UNICODE), true);
    }
    /**
    * @return array<string, array<string, string>> The display of the cards in the deck.
    */
    public function getDisplay()
    {

        return $this->cardDisplay;


    }

    /*
    Sets the display of the cards in the deck, 
    by creating an array of the cards.
    Each card is represented by its graphic and color.
    */
    public function setDisplay(): void
    {
        $this->cardDisplay = array();
        foreach ($this->cards as $currcard) {
            $this->cardDisplay[$currcard->getAsGraphic()]["Color: "] = $currcard->getColor();
        }
    }

    /**
    Shuffles the cards in the deck and sets the display.
    */
    public function shuffleCards(): void
    {

        shuffle($this->cards);
        $this->setDisplay();

    }

    public function sortCards(): void
    {

        usort($this->cards, function ($avar, $bvar) {
            return $avar->getcardNumrep() <=> $bvar->getcardNumrep();
        });
        $this->setDisplay();
    }
    
    /**
    * Returns the cards in the deck.
    * @return Card[] An array of Card objects representing the cards in the deck.
    */
    public function getCards()
    {
        return $this->cards;
    }
   /**
     * Draws a specified number of cards from the deck.
     *
     * @param int|null $numofCards The number of cards to draw from the deck. If null, no cards are drawn.
     * @return array<Card> An array of Card objects that have been drawn from the deck.
     */
    public function drawCard($numofCards = null)
    {
        $heldCards = [];

        for ($i = 0; $i < $numofCards; $i++) {
            if (!empty($this->cards)) {
                $heldCards[] = array_pop($this->cards); // Add Card objects to the heldCards array
            }
        }

        $this->setDisplay(); // Update the cardDisplay to reflect the current state of the deck

        return $heldCards;

    }

    /**
     * Returns the number of cards left in the deck.
     *
     * @return int The number of cards left in the deck.
     */
    public function getcardsLeft(): int
    {
        return count($this->cards);
    }









}
