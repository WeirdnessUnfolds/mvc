<?php

namespace App\Card;

use App\Controller;
use App\Card\Player;
use App\Card\CardHand;

class CpuPlayer extends Player
{
    public function __construct()
    {
        parent::__construct("CPU");
    }
    /**
    CPU players strategy for making a move.
    The CPU player will draw a card if its points are less than 17,
    otherwise it will stop drawing cards.
    The CPU player will not draw a card if it has already stopped.
     * @param Card $card The card to be drawn.
     */
    public function makeMove($card = null): string
    {
        return $this->getPoints() >= 17 ? "stop" : "draw";
    }

}
