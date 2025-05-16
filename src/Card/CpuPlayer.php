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

    public function makeMove($card = null): string
    {
        return $this->getPoints() >= 17 ? "stop" : "draw";
    }
 
}