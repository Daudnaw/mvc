<?php

namespace App\Card;

use App\Card\Deck;

/**
 * Class Game
 *
 * This class inherits from Deck and some function to play twentyOne
 */
class Game extends Deck
{
    /**
     * Creates new Deck of 52 cards without jokers
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This function gives rank as integer and also a number for A, K, Q and J
     *
     * @return int $rank as an integer
     */
    public function getRank(Card $card): int
    {
        $rank = $card->getRank();

        $cardValues = [
            'A' => 14,
            'K' => 13,
            'Q' => 12,
            'J' => 11
        ];

        return $cardValues[$rank] ?? (int) $rank;
    }

    /**
     * Decider of the winer of the game 21
     *
     * @param int $spelare points spelare has collected.
     * @param int $bank points bank has collected.
     * @return string returns a string bank or spelare
     */
    public function winLose(int $spelare, int $bank): string
    {
        if ($bank > 21) {
            return "spelare";
        }

        return ($bank >= $spelare || $bank === 21) ? "bank" : "spelare";
    }
}
