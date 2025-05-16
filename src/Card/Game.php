<?php

namespace App\Card;
use App\Controller;
use App\Card\Card;
use App\Card\Player;
use App\Card\CpuPlayer;
use App\Card\DeckOfCards;


class Game {

    /**
     * A class that represents a game of cards.
     * The game has a player, a CPU player, and a deck of cards.
     * The game can be played by drawing cards and determining the winner.
     */

    public $player;
    public $cpuPlayer;
    public $deck;
    public $playerstopped = false;
    public $cpustopped = false;
    public function __construct(Player $player, CpuPlayer $cpuPlayer, DeckOfCards $deck)
    {
        $this->player = $player;
        $this->cpuPlayer = $cpuPlayer;
        $this->deck = $deck;
        $this->deck->shuffleCards();
    }


    public function playerHasStopped(): bool
    {
        // Implement logic to check if player has stopped
    }

    public function cpuHasStopped(): bool
    {
        // Implement logic to check if CPU has stopped
    }

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

    public function playGame($playerAction)
    {
        // First turn: deal two cards each
        if ($playerAction === "first_turn") {
            for ($i = 0; $i < 2; $i++) {
                $this->player->drawCard($this->deck->drawCard(1));
                $this->cpuPlayer->drawCard($this->deck->drawCard(1));
            }
            if ($this->player->getPoints() > 21 || $this->cpuPlayer->getPoints() > 21) {
                return $this->getWinner();
            } 
            return "Ongoing";
        }

        // Player draws
        if ($playerAction === "draw" && !$this->playerstopped) {
            $this->player->drawCard($this->deck->drawCard(1));
            if ($this->player->getPoints() > 21) {
                $this->playerstopped = true;
            }
        }

        // Player stops
        if ($playerAction === "stop") {
            $this->playerstopped = true;
        }

        // CPU's turn: only act if player has stopped and CPU hasn't
        if ($this->playerstopped && !$this->cpustopped) {
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
        $this->cpustopped = true;

        // If both have stopped, or if either busts, calculate winner
        if (
            ($this->playerstopped && $this->cpustopped) ||
            $this->player->getPoints() > 21 ||
            $this->cpuPlayer->getPoints() > 21
        ) {
            return $this->getWinner();
        }

        return "Ongoing";
    }
        


        


}