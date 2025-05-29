<?php

namespace App\Card;


use PHPUnit\Framework\TestCase;
use App\Controller;

/**
 * Test cases for the Deckofcards.
 */
class DeckOfCardsTest extends TestCase
{       

    public function testCreateObject() {
        $deck = new DeckOfCards();
        $this->assertInstanceOf(DeckOfCards::class, $deck);
        $this->assertCount(52, $deck->getCards());  // There should be 52 cards
    }

    public function testGetCards() {
        $deck = new DeckOfCards();
        $this->assertIsArray($deck->getCards());
        $this->assertCount(52, $deck->getCards()); 
    }
      
    public function testGetCardsLeft() {
        $deck = new DeckOfCards();
        $this->assertEquals(52, $deck->getcardsLeft());
        // When no card has been drawn, there are 52 left.
    }

    public function testDrawOneCard() {
        $deck = new DeckofCards();
        $heldcards = $deck->drawCard(1);
        
        $this->assertCount(1, $heldcards); // One card has been drawn.
        $this->assertEquals(51, $deck->getcardsLeft()); // There are 51 cards left.

    }

    public function testDrawThreeCards() {
        $deck = new DeckOfCards();
        $heldcards = $deck->drawCard(3);
        
        $this->assertCount(3, $heldcards); // One card has been drawn.
        $this->assertEquals(49, $deck->getcardsLeft()); // There are 49 cards left.

    }

    public function testShuffleCards() {
    $deck = new DeckOfCards();
    $originalOrder = $deck->getCards();
    $deck->shuffleCards();
    $shuffledOrder = $deck->getCards();
        
    $this->assertNotEquals($originalOrder, $shuffledOrder);
    $this->assertEqualsCanonicalizing($originalOrder, $shuffledOrder);
    }

    public function testSorting() {
        $deck = new DeckOfCards();
        $deck->shuffleCards();
        $deck->sortCards();

        $sorteddeck = $deck->getCards();

        $expdeck = new DeckOfCards();
        $exposorted = $expdeck->getCards();

        $this->assertEquals($exposorted, $sorteddeck);
    }

    public function testSortingPartialDeck()
{
    $deck = new DeckOfCards();
    $deck->drawCard(5); // Remove 5 cards
    $deck->shuffleCards();
    $deck->sortCards();

    $sortedPartial = $deck->getCards();
    // Create the expected sorted partial deck
    $actualValues = array_map(fn($card) => $card->getCardNumRep(), $sortedPartial);

    // Create the expected sorted values (cards 6 to 52)
    $expectedValues = range(1, 47);

    $this->assertEquals($expectedValues, $actualValues);
}

public function testGetDisplay() {
    $deck = new DeckOfCards();

    $display = $deck->getDisplay();

    $this->assertIsArray($display);

    foreach ($display as $graphic => $attributes) {
        $this->assertIsArray($attributes);
        // A Color: key
        $this->assertArrayHasKey("Color: ", $attributes);
        // Colors are either red of black
        $this->assertContains($attributes["Color: "], ["Red", "Black"]);
    }
}

public function testGetDisplayAPI() {
     $deck = new DeckOfCards();

    $display = $deck->getDisplayAPI();

    $this->assertIsArray($display);
    foreach ($display as $graphic => $attributes) {
        $this->assertIsArray($attributes);
        // A Color: key
        $this->assertArrayHasKey("color", $attributes);
        // Colors are either red of black
        $this->assertContains($attributes["color"], ["Red", "Black"]);
        $this->assertArrayHasKey("number", $attributes);
    }

}

}