<?php

namespace App\Card;


use PHPUnit\Framework\TestCase;
use App\Controller;

/**
 * Test cases for the player class.
 */
class CardHandTest extends TestCase
{       
        private ?Card $firstcard;
        private ?Card $secondcard;
        private ?CardHand $cardhandMultiple;
        private ?CardHand $cardhandSingle;

       protected function setUp(): void
    {
        $this->firstcard = new Card(22);
        $this->secondcard = new Card(54);
        $this->cardhandMultiple = new CardHand([$this->firstcard, $this->secondcard]);
        $this->cardhandSingle = new CardHand([$this->firstcard]);
    }

      protected function tearDown(): void
    {
        $this->firstcard = null;
        $this->secondcard = null;
        $this->cardhandMultiple = null;
        $this->cardhandSingle = null;
    }


    public function testcreateObjectMultipleCards() {
   
        $this->assertInstanceOf(CardHand::class, $this->cardhandMultiple);
    }

    public function testcreateObjectOneCard() {
        $this->assertInstanceOf(CardHand::class, $this->cardhandSingle);
    }

   
    public function testViewHand() {
        $hand = $this->cardhandMultiple->viewHand();

        $this->assertIsarray($hand);

        foreach ($hand as $attributes) {
            $this->assertArrayHasKey("Color: ", $attributes);
        }

    }

    public function testaddCard() {
        $mockCard = $this->createMock(Card::class);
        $mockCard->method('getAsGraphic')->willReturn('MOCK_CARD');
        $mockCard->method('getColor')->willReturn('Black');

        $cardHand = new CardHand([]);
        $cardHand->addCard($mockCard);

        $hand = $cardHand->viewHand();

        $this->assertArrayHasKey('MOCK_CARD', $hand);
        $this->assertEquals('Black', $hand['MOCK_CARD']['Color: ']);
    }

    public function testGetPoints() {
        $mockCard1 = $this->createMock(Card::class);  
        $mockCard2 = $this->createMock(Card::class);

        $mockCard1->method('getCardPoints')->willReturn(10);
        $mockCard2->method('getCardPoints')->willReturn(5);
        $cardHand = new CardHand([$mockCard1, $mockCard2]);

        $this->assertEquals(15, $cardHand->getPoints());
    }
   

}