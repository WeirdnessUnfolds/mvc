<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use App\Controller;

/**
 * Test cases for the game.
 */
class GameTest extends TestCase
{
    private $mockplayer;
    private $mockcpu;
    private $mockdeck;
    private $mockcard;

    protected function setUp(): void
    {
        $this->mockplayer = $this->createMock(Player::class);
        $this->mockcpu = $this->createMock(CpuPlayer::class);
        $this->mockdeck = $this->createMock(DeckOfCards::class);
        $this->mockcard = $this->createMock(Card::class);
    }

    protected function tearDown(): void
    {
        $this->mockplayer = null;
        $this->mockcpu = null;
        $this->mockdeck = null;
        $this->mockcard = null;
    }

    public function testConstructor()
    {
        // Create a mock for DeckOfCards to check if shuffleCards is called
        $deck = $this->getMockBuilder(DeckOfCards::class)
            ->onlyMethods(['shuffleCards'])
            ->getMock();
        $deck->expects($this->once())->method('shuffleCards');

        $game = new Game($this->mockplayer, $this->mockcpu, $deck);

        $this->assertInstanceOf(Game::class, $game);
    }

    public function testGetWinnerTieBoth()
    {
        // For a tie
        $this->mockplayer->method('getPoints')->willReturn(23);
        $this->mockcpu->method('getPoints')->willReturn(26);

        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        $this->assertEquals('Oavgjort', $game->getWinner());




    }

    public function testGetWinnerPlayerMore() {
        $this->mockplayer->method('getPoints')->willReturn(21);
        $this->mockcpu->method('getPoints')->willReturn(22);
        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        $this->assertEquals('Spelaren', $game->getWinner());
    }

    public function testGetWinnerPlayerLess() {
        // Player wins, player 21 and bank less than player
        $this->mockplayer->method('getPoints')->willReturn(21);
        $this->mockcpu->method('getPoints')->willReturn(18);
        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        $this->assertEquals('Spelaren', $game->getWinner());

    }
    public function testGetWinnerBank(){
        
        // Bank wins scenario, bank more than player and less than 21
        $this->mockplayer->method('getPoints')->willReturn(18);
        $this->mockcpu->method('getPoints')->willReturn(20);
        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        $this->assertEquals('Banken', $game->getWinner());
    }

    public function testGetWinnerTie() {
        // Tied, same score
        $this->mockplayer->method('getPoints')->willReturn(21);
        $this->mockcpu->method('getPoints')->willReturn(21);
        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        $this->assertEquals('Oavgjort', $game->getWinner());
    }

    public function testGetWinnerBankMore() {
        $this->mockplayer->method('getPoints')->willReturn(22);
        $this->mockcpu->method('getPoints')->willReturn(18);
        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        $this->assertEquals('Banken', $game->getWinner());
    }

    public function testPlayGameFirstTurn()
    {
        $this->mockdeck->method('drawCard')->willReturn([$this->mockcard]);
        $this->mockplayer->method('getPoints')->willReturn(18);
        $this->mockcpu->method('getPoints')->willReturn(16);

        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        // Ongoing game 
        $gameaction = $game->playGame("first_turn");
        $this->assertEquals('Ongoing', $gameaction);
    }

    public function testPlayGameFirstTurnWinBank()
    {
        $this->mockdeck->method('drawCard')->willReturn([$this->mockcard]);
        $this->mockplayer->method('getPoints')->willReturn(23);
        $this->mockcpu->method('getPoints')->willReturn(16);

        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        // Ongoing game 
        $gameaction = $game->playGame("first_turn");
        $this->assertEquals('Banken', $gameaction);
    }

    public function testPlayGameFirstTurnWinPlayer()
    {
        $this->mockdeck->method('drawCard')->willReturn([$this->mockcard]);
        $this->mockplayer->method('getPoints')->willReturn(18);
        $this->mockcpu->method('getPoints')->willReturn(23);

        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        // Ongoing game 
        $gameaction = $game->playGame("first_turn");
        $this->assertEquals('Spelaren', $gameaction);
    }

    public function testPlayGamePlayerDraw() {
        $this->mockdeck->method('drawCard')->willReturn([$this->mockcard]);
        $this->mockplayer->method('getPoints')->willReturn(7);
        $this->mockcpu->method('getPoints')->willReturn(17);

        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        // Ongoing game 
        $gameaction = $game->playGame("draw");
        $this->assertEquals(false, $game->playerstopped);
    }

    public function testPlayGamePlayerDrawOver21() {
        // If the player draws a card that makes the points
        // go over 21.
        $this->mockdeck->method('drawCard')->willReturn([$this->mockcard]);
        $this->mockplayer->method('getPoints')->willReturn(22);
        $this->mockcpu->method('getPoints')->willReturn(17);

        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        // Ongoing game 
        $gameaction = $game->playGame("draw");
        $this->assertEquals(true, $game->playerstopped);
    }

    public function testPlayGamePlayerStop() {
        // If the player stops before 21 is reached.
        $this->mockdeck->method('drawCard')->willReturn([$this->mockcard]);
        $this->mockplayer->method('getPoints')->willReturn(18);
        $this->mockcpu->method('getPoints')->willReturn(17);

        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        // Ongoing game 
        $gameaction = $game->playGame("stop");
        $this->assertEquals(true, $game->playerstopped);

    }


    public function testHandleCpuTurn()
    {
        // CPU will draw, then stop.
        $this->mockcpu->method('makeMove')
        ->willReturnCallback(function() {
        static $calls = 0;
        return ++$calls < 3 ? 'draw' : 'stop';
        });
        $this->mockdeck->method('drawCard')->willReturn([$this->mockcard]);

        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);

        $game->handleCpuTurn();


        $this->assertTrue($game->cpustopped);
}

    public function testPlayGameCpuStopOver21() {
        // If the player stops before 21 is reached.
        $this->mockdeck->method('drawCard')->willReturn([$this->mockcard]);
        $this->mockplayer->method('getPoints')->willReturn(18);
        $this->mockcpu->method('getPoints')->willReturn(22);
        $this->mockcpu->method('makeMove')->willReturn('draw');

        $game = new Game($this->mockplayer, $this->mockcpu, $this->mockdeck);
        // Ongoing game 
        $game->handleCpuTurn();
        $this->assertEquals(true, $game->cpustopped);

    }

}
