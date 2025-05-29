<?php

namespace App\Card;


use PHPUnit\Framework\TestCase;
use App\Controller;

/**
 * Test cases for the card class.
 */
class PlayerTest extends TestCase
{   
    private ?Player $player;
    private ?Player $playerdraw;

    protected function setUp(): void
    {
        $this->player = new Player("Player");
        $this->playerdraw = new Player("Player");
        

    }



    public function testCreateObjectWithArguments()
    {
        $playercreate = new Player("Player");
        // Assert that a player is created
        $this->assertInstanceOf(Player::class, $playercreate); 
 
    }

    public function testgetName() {
        $name = $this->player->getName();
        $this->assertEquals("Player", $name);
    }

    public function testgetPoints() {

        $card = new Card(10); // Jack, 11 points
        $othercard = new Card(5); // 6 points
        $this->playerdraw->drawCard([$card]);
        $this->playerdraw->drawCard([$othercard]);


        $points = $this->playerdraw->getpoints();
        $this->assertEquals(17, $points);        

    }

    public function testdrawCard() {
        $card = new Card(10); // Jack, 11 points

        $this->playerdraw->drawCard([$card]);

        $hand = $this->playerdraw->getHand()->viewHand();
        $graphic = $card->getAsGraphic();

        $this->assertArrayHasKey($graphic, $hand);  
    }

     public function testdrawCardSeveral() {
        $card = new Card(10);
        $othercard = new Card(12);
        $this->playerdraw->drawCard([$card, $othercard]);

        $hand = $this->playerdraw->getHand()->viewHand();
        $graphic = $card->getAsGraphic();

        $this->assertArrayHasKey($graphic, $hand);  
    }

    public function testviewHand() {
        $card = new Card(10); // Jack, 11 points
        $othercard = new Card(5); // 6 points
        $this->playerdraw->drawCard([$card]);
        $this->playerdraw->drawCard([$othercard]);
        
        $hand = $this->playerdraw->viewHand();

        $this->assertIsArray($hand);


        $this->assertArrayHasKey($card->getAsGraphic(), $hand);
        $this->assertArrayHasKey($othercard->getAsGraphic(), $hand);

        }

    public function testViewHandSingleCard()  {
        $card = new Card(7);
        $this->player->drawCard([$card]);

        $hand = $this->player->viewHand();

        $this->assertIsArray($hand);
        $this->assertCount(1, $hand);
        $this->assertArrayHasKey($card->getAsGraphic(), $hand);
}

    public function tearDown(): void {
        $this->player = null;
        $this->playerdraw = null;

    }

}