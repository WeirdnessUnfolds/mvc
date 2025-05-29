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
    private string $name;
    private CardHand $hand;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hand = new CardHand([]);
    }

    /**
    * Getter for the name of the player. CPU or Player.
    * @return string The name of the player.
    */
    public function getName(): string
    {
        return $this->name;
    }

    /**
    * Getter for the hand of the player.
    * @return CardHand The hand of the player.
    */
    public function getHand(): CardHand
    {
        return $this->hand;
    }

    /**
    * Draws a card from the deck and adds it to the player's hand.
    * @param array <Card> $card The card to be drawn.
    * @return void
    */
    public function drawCard(array $card): void
    {

        $this->hand->addCard($card[0]);
    }

    /**
    * Gets the points of the players hand.
    * @return int The points of the players hand.
    */
    public function getPoints(): int
    {
        return $this->hand->getPoints();
    }


    /**
    * Displays the players hand.
    * @return array<string, array<string, string>> The display of the players hand.
    */
    public function viewHand(): array
    {

        return $this->hand->viewHand();
    }
}
