<?php

namespace App\Card;

use App\Controller;

/*
A class that represents a card.
*/

class Card
{   /* Represents the color and value of the card, random
    number between 1 and 53. */
    protected $cardvalue;
    protected $cardgraphic;
    private $utf8_card;
    public function __construct($enteredvalue = null)
    {
        if ($enteredvalue == null) { // If no value between 1 and 53 is entered, a random card is shown.
            $this->cardvalue = random_int(1, 53);
        } else {
            $this->cardvalue = $enteredvalue;
        }
        $this->cardgraphic = null;
    }

    public function getcardNumrep(): int
    {
        return $this->cardvalue;
    }

    public function getCardPoints(): int
    {
        if ($this->cardvalue == 53) { // The number 53 represents the joker.
            return 0;
        }

        $rank = $this->cardvalue % 13; // Determine the rank within the suit (0-12)

        if ($rank == 0) { // Ace (last card in the suit)
            return 14;
        } elseif ($rank >= 1 && $rank <= 9) {
            return $rank + 1; // Number cards (2-10)
        } elseif ($rank == 10) {
            return 11; // Jack
        } elseif ($rank == 11) {
            return 12; // Queen
        } elseif ($rank == 12) {
            return 13; // King
        }

        return 0; // Fallback
    }

    public function getColor()
    {

        $color = "";
        if ($this->cardvalue <= 13) {
            $color = "Black";
        } elseif ($this->cardvalue > 13 and $this->cardvalue <= 26) {
            $color = "Red";
        } elseif ($this->cardvalue > 26 and $this->cardvalue <= 39) {
            $color = "Red";

        } elseif ($this->cardvalue > 39 and $this->cardvalue <= 52) {
            $color = "Black";

        }

        return $color;



    }

    public function getAsGraphic(): string
    {
        if ($this->cardvalue <= 13) {
            $this->utf8_card  = mb_ord("🂡", "UTF-8");
        } elseif ($this->cardvalue > 13 and $this->cardvalue <= 26) {
            $this->utf8_card = mb_ord("🂱", "UTF-8");
        } elseif ($this->cardvalue > 26 and $this->cardvalue <= 39) {
            $this->utf8_card  = mb_ord("🃁", "UTF-8");

        } elseif ($this->cardvalue > 39 and $this->cardvalue <= 52) {
            $this->utf8_card  = mb_ord("🃑", "UTF-8");

        }

        if ($this->cardvalue == 53) {  // The number 53 represents the joker.
            $this->utf8_card = mb_ord("🂿", "UTF-8");

        } else {
            $rank = $this->cardvalue % 13;
            for ($i = 1; $i <= $rank; $i++) {

                // Skip the Unicode values for the Knights
                if ($this->utf8_card == mb_ord("🂫", "UTF-8") || // Knight of Spades
                    $this->utf8_card == mb_ord("🂻", "UTF-8") || // Knight of Hearts
                    $this->utf8_card == mb_ord("🃋", "UTF-8") || // Knight of Diamonds
                    $this->utf8_card == mb_ord("🃛", "UTF-8")) { // Knight of Clubs
                    ++$this->utf8_card;
                }
                ++$this->utf8_card;
            }
        }

        return mb_chr($this->utf8_card, "UTF-8");
    }

    public function serialize(): string
    {
        return serialize([
            'cardvalue' => $this->cardvalue,
            'cardgraphic' => $this->cardgraphic,
            'utf8_card' => $this->utf8_card,
        ]);
    }

    public function unserialize($data): void
    {
        $unserializedData = unserialize($data);
        $this->cardvalue = $unserializedData['cardvalue'];
        $this->cardgraphic = $unserializedData['cardgraphic'];
        $this->utf8_card = $unserializedData['utf8_card'];
    }


}
