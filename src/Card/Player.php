<?php

namespace App\Card;

use App\Controller;

class Player
{
    /**
    *
    * A class that represents a player in the game.
    * The player has a name and a hand of cards,
    * can draw cards and view their hand, and get the points of it.
    */
    private $name;
    private $hand;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hand = new CardHand([]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHand(): CardHand
    {
        return $this->hand;
    }

    public function drawCard($card): void
    {

        $this->hand->addCard($card[0]);
    }

    public function getPoints(): int
    {
        return $this->hand->getPoints();
    }

    

    public function viewHand(): array
    {
        dump($this->hand);
        dump($this->hand->viewHand());
        return $this->hand->viewHand();
    }
}