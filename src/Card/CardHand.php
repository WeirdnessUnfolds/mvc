<?php

namespace App\Card;

use App\Controller;
use App\Card\Card;
use App\Card\Player;
use App\Card\DeckOfCards;
use App\Card\DeckOfCardsJoker;

/*
A class that represents a hand of cards that draws cards from a cardHand.
*/

class CardHand
{   /* Displays the cards that have been drawn from a deck in a hand. */
    /** @var Card[] */
    private array $cardsInhand;
    /** @var array<string, array<string, string>> */
    private array $graphicarray = array();

    /**
    * @param Card[] $drawnCards
    */
    public function __construct(array $drawnCards)
    {

        $this->cardsInhand = $drawnCards;
    }

    /** @return array<string, array<string, string>> */
    public function viewHand(): array
    {
        foreach ($this->cardsInhand as $currcard) {
            $this->graphicarray[$currcard->getAsGraphic()]["Color: "] = $currcard->getColor();
        }
        return $this->graphicarray;
    }

    public function addCard(Card $card): void
    {
        $this->cardsInhand[] = $card;
    }

    public function getPoints(): int
    {
        $totalpoints = 0;
        $cardsInhand = $this->cardsInhand;
        foreach ($this->cardsInhand as $currcard) {
            dump($totalpoints);
            $totalpoints += $currcard->getCardPoints();
        }
        return $totalpoints;
    }

}
