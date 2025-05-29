<?php

namespace App\Card;

use App\Controller;
use App\Card\Card;
use App\Card\Player;
use App\Card\CpuPlayer;
use App\Card\DeckOfCards;

class Game
{
    /**
     * A class that represents a game of cards.
     * The game has a player, a CPU player, and a deck of cards.
     * The game can be played by drawing cards and determining the winner.
     */

    public Player $player;
    public CpuPlayer $cpuPlayer;
    public DeckOfCards $deck;
    public bool $playerstopped = false;
    public bool $cpustopped = false;
    public function __construct(Player $player, CpuPlayer $cpuPlayer, DeckOfCards $deck)
    {
        $this->player = $player;
        $this->cpuPlayer = $cpuPlayer;
        $this->deck = $deck;
        $this->deck->shuffleCards();
    }



    /**
     *  The winner is determined by the points of the player and the CPU player.
     * If both players bust, the result is a tie.
     * If the player busts, the CPU player wins.
     * If the CPU player busts, the player wins.
     * @return string $winner - The winner of the game.
     * 
     */
    public function getWinner(): string
    {
        $playerPoints = $this->player->getPoints();
        $cpuPoints = $this->cpuPlayer->getPoints();

        // Both bust
        if ($playerPoints > 21 && $cpuPoints > 21) {
            return "Oavgjort";
        }
        // Player busts
        if ($playerPoints > 21) {
            return "Banken";
        }
        // CPU busts
        if ($cpuPoints > 21) {
            return "Spelaren";
        }
        // Both under or equal 21, compare scores
        if ($playerPoints > $cpuPoints) {
            return "Spelaren";
        }
        if ($cpuPoints > $playerPoints) {
            return "Banken";
        }
        // Tie
        return "Oavgjort";

    }

    /**
    * The main game loop that handles the player's actions and the CPU's actions.
    * The player can draw cards or stop drawing cards.
    * The CPU will draw cards based on its strategy.
    * The game continues until a winner is determined or both players stop drawing cards.
    * @param string $playerAction The action taken by the player.
    * @return string $winner - The winner of the game.
    */
    public function playGame(string $playerAction): string
    {
        if ($playerAction === "first_turn") {
            return $this->handleFirstTurn();
        }

        if ($playerAction === "draw" && !$this->playerstopped) {
            $this->handlePlayerDraw();
        }

        if ($playerAction === "stop") {
            $this->playerstopped = true;
        }

        if ($this->playerstopped && !$this->cpustopped) {
            $this->handleCpuTurn();
        }

        if ($this->isGameOver()) {
            return $this->getWinner();
        }

        return "Ongoing";
    }

    private function handleFirstTurn(): string
    {
        $this->dealFirstTurn();
        if ($this->player->getPoints() > 21 || $this->cpuPlayer->getPoints() > 21) {
            return $this->getWinner();
        }
        return "Ongoing";
    }

    private function isGameOver(): bool
    {
        return ($this->playerstopped && $this->cpustopped)
            || $this->player->getPoints() > 21
            || $this->cpuPlayer->getPoints() > 21;
    }

    private function dealFirstTurn(): void
    {
        for ($i = 0; $i < 2; $i++) {
            $this->player->drawCard($this->deck->drawCard(1));
            $this->cpuPlayer->drawCard($this->deck->drawCard(1));
        }
    }

    private function handlePlayerDraw(): void
    {
        $this->player->drawCard($this->deck->drawCard(1));
        if ($this->player->getPoints() > 21) {
            $this->playerstopped = true;
        }
    }

    public function handleCpuTurn(): void
    {
        while ($this->cpuPlayer->makeMove() === "draw") {
            $this->cpuPlayer->drawCard($this->deck->drawCard(1));
            if ($this->cpuPlayer->getPoints() > 21) {
                $this->cpustopped = true;
                break;
            }
        }
        if ($this->cpuPlayer->makeMove() === "stop") {
            $this->cpustopped = true;
        }
    }
}
