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
     *
     */
    public function makeMove(): string
    {
        return $this->getPoints() >= 17 ? "stop" : "draw";
    }

}
