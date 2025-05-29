<?php

namespace App\Card;

use App\Controller;

/*
A class that represents a card.
*/

class Card
{
    /* Represents the color and value of the card, random
    number between 1 and 53. */
    protected int $cardValue;
    protected ?string $cardGraphic;
    private ?int $utf8Card;

    public function __construct(?int $enteredValue = null)
    {
        if ($enteredValue == null) { // If no value between 1 and 53 is entered, a random card is shown.
            $enteredValue = random_int(1, 53);

        }

        $this->cardValue = $enteredValue;
        $this->cardGraphic = null;
        $this->utf8Card = null;
    }

    /**
     * Returns the card value, the numeric representation of its place
     * in the deck.
     *
     * @return int The card value.
     */
    public function getCardNumRep(): int
    {
        return $this->cardValue;
    }

    /**
     * Returns the points that the card is worth.
     *
     * @return int The card worth.
     */
    public function getCardPoints(): int
    {
        if ($this->cardValue == 53) { // The number 53 represents the joker.
            return 0;
        }

        $rank = $this->cardValue % 13; // Determine the rank within the suit (0-12)

        if ($rank == 0) { // Ace (last card in the suit)
            return 14;
        } elseif ($rank >= 1 && $rank <= 9) {
            return $rank + 1; // Number cards (2-10)
        } elseif ($rank == 10) {
            return 11; // Jack
        } elseif ($rank == 11) {
            return 12; // Queen
        } elseif ($rank == 12) {
            return 13; // King
        }

        return 0; // Fallback
    }

    /**
    * Returns the color of the card based on the value in the deck,
    * provided that it's sorted.
    */
    public function getColor(): string
    {
        $color = "";
        if ($this->cardValue <= 13) {
            $color = "Black";
        } elseif ($this->cardValue <= 26) {
            $color = "Red";
        } elseif ($this->cardValue <= 39) {
            $color = "Red";
        } elseif ($this->cardValue <= 52) {
            $color = "Black";
        }

        return $color;
    }

    /**
     * Returns the card graphic as a string.
     *
     * @return string The card graphic.
     */
    public function getAsGraphic(): string
    {
        // Handle Joker
        if ($this->cardValue === 53) {
            return "🂿";
        }

        // Determine suit base
        $suitBases = [
            1 => mb_ord("🂡", "UTF-8"),   // Spades
            14 => mb_ord("🂱", "UTF-8"),  // Hearts
            27 => mb_ord("🃁", "UTF-8"),  // Diamonds
            40 => mb_ord("🃑", "UTF-8"),  // Clubs
        ];

        // Find the suit base
        foreach (array_reverse(array_keys($suitBases)) as $start) {
            if ($this->cardValue >= $start) {
                $this->utf8Card = $suitBases[$start];
                break;
            }
        }

        $rank = $this->cardValue % 13;
        $knights = [
            mb_ord("🂫", "UTF-8"), // Knight of Spades
            mb_ord("🂻", "UTF-8"), // Knight of Hearts
            mb_ord("🃋", "UTF-8"), // Knight of Diamonds
            mb_ord("🃛", "UTF-8"), // Knight of Clubs
        ];

        for ($i = 1; $i <= $rank; $i++) {
            if (in_array($this->utf8Card, $knights, true)) {
                ++$this->utf8Card;
            }
            ++$this->utf8Card;
        }

        return mb_chr($this->utf8Card, "UTF-8");
    }
    /**
    * Serializes the card data to a string.
    *
    * @return string The serialized card data.
    */
    public function serialize(): string
    {
        return serialize([
            'cardValue' => $this->cardValue,
            'cardGraphic' => $this->cardGraphic,
            'utf8Card' => $this->utf8Card,
        ]);
    }

    /**
     * Unserializes the card data from a string.
     *
     * @param int|string $data The serialized card data.
     */
    public function unserialize(int|string $data): void
    {
        $unserializedData = unserialize($data);
        $this->cardValue = $unserializedData['cardValue'];
        $this->cardGraphic = $unserializedData['cardGraphic'];
        $this->utf8Card = $unserializedData['utf8Card'];
    }
}
