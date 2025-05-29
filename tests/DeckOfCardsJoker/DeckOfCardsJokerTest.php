<?php

namespace App\Card;


use PHPUnit\Framework\TestCase;
use App\Controller;

/**
 * Test cases for the Deckofcards.
 */
class DeckOfCardsJokerTest extends TestCase
{       

    public function testCreateObject() {
        $deck = new DeckOfCardsJoker();
        $display = $deck->getDisplay();
        $this->assertInstanceOf(DeckOfCardsJoker::class, $deck);
        $this->assertCount(53, $deck->getCards());
        print_r($display);

        foreach ($display as $graphic => $attributes) {
        $this->assertIsArray($attributes);
        $this->assertContains($attributes["Color: "], ["Red", "Black", ""]);
        // Only color is enough here, since we won't need to list
        // all possible cards.

    }

        // There should be 53 cards, joker included.
    }



}