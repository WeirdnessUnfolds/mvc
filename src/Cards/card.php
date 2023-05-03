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
            for($i = 1; $i <= ($this->cardvalue % 13); $i++) {
                ++$this->utf8_card;
            }
        }

        return mb_chr($this->utf8_card, "UTF-8");
    }






}
