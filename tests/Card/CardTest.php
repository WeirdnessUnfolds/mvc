<?php

namespace App\Card;


use PHPUnit\Framework\TestCase;
use App\Controller;

/**
 * Test cases for the card class.
 */
class CardTest extends TestCase
{
    

    public function testCreateObjectNoArguments()
    {
        $card = new Card();
        // Assert that card is created
        $this->assertInstanceOf(Card::class, $card); 
        // Assert that the numrep is an int
        $value = $card->getCardNumRep();
        $this->assertIsInt($value);
        $this->assertGreaterThanOrEqual(1, $value);
        $this->assertLessThanOrEqual(53, $value);
    }

    public function testCreateObjectWithArguments($arg = 34) {
        $card = new Card($arg);
        $value = $card->getCardNumRep();
        $this->assertInstanceOf(Card::class, $card); 
        // Assert that the numrep is an int
        $value = $card->getCardNumRep();
        $this->assertIsInt($value);
        $this->assertEquals($arg, $value);

    }

    public function cardPointsProvider(): array
    {
        return [
            [13, 14],   // Ace
            [1, 2],    // 2
            [9, 10],  // 10
            [10, 11],  // Jack
            [11, 12],  // Queen
            [12, 13],  // King
            [53, 0],   // Joker or invalid
            [-3, 0]
    ];
    }

    public function cardColors(): array
    {
        return [
            [1, "Black"],
            [13, "Black"],
            [14, "Red"],
            [39, "Red"],  // 10
            [40, "Black"],  // Jack
            [5, "Black"]
    ];


    }

    /**
    * @dataProvider cardPointsProvider
    */
    public function testgetCardPoints(int $cardValue, int $expectedPoints) {
        $card = new Card($cardValue);
        $this->assertEquals($expectedPoints, $card->getCardPoints());
    }

    /**
    * @dataProvider cardColors
    */
    public function testgetColor(int $cardValue, string $expColor) {
        $card = new Card($cardValue);
        $this->assertEquals($expColor, $card->getColor());
    }

    public function testAsGraphic()
{
    // Test Joker
    $joker = new Card(53);
    $this->assertEquals("🂿", $joker->getAsGraphic());

    // Test a range of valid cards (not checking exact symbol
    foreach ([1, 13, 14, 27, 40, 52] as $cardValue) {
        $card = new Card($cardValue);
        $graphic = $card->getAsGraphic();
        $this->assertIsString($graphic);
        $this->assertNotEmpty($graphic);
        $this->assertEquals(1, mb_strlen($graphic, "UTF-8"));
    }
}

    public function testCardSerialization() {
        $original = new Card(10);


        $serialized = $original->serialize();
        
        $unserialized = new Card(1); // Value doesn't matter here
        $unserialized->unserialize($serialized);

        $this->assertInstanceOf(Card::class, $unserialized);
        $this->assertEquals($original->getCardNumRep(), $unserialized->getCardNumRep());
        $this->assertEquals($original->getColor(), $unserialized->getColor());
        $this->assertEquals($original->getAsGraphic(), $unserialized->getAsGraphic());
    }


}