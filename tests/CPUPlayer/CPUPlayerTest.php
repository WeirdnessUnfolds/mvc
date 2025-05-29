<?php

namespace App\Card;


use PHPUnit\Framework\TestCase;
use App\Controller;

/**
 * Test cases for the card class.
 */


 // Double for points

class TestCpuPlayer extends CpuPlayer
{
    private int $mockPoints = 0;

    public function setMockPoints(int $points): void
    {
        $this->mockPoints = $points;
    }

    public function getPoints(): int
    {
        return $this->mockPoints;
    }
}


class CPUPlayerTest extends TestCase
{   


    public function testCreateObject()
    {
        $playercreate = new CpuPlayer();
        // Assert that a player is created
        $this->assertInstanceOf(CpuPlayer::class, $playercreate); 
 
    }

    public function testMakeMove() {
        // We mock what the points will be
        $cpuplayer = new TestCpuPlayer();
        
        $cpuplayer->setMockPoints(16); // Draw
        $this->assertEquals('draw', $cpuplayer->makeMove());
        $cpuplayer->setMockPoints(17); // Stop
        $this->assertEquals('stop', $cpuplayer->makeMove());

    }

}