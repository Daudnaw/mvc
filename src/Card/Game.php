<?php

namespace App\Card;

use App\Card\Deck;

class Game extends Deck
{
    public function __construct()
    {
        parent::__construct();
    }

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

    public function winLose(int $spelare, int $bank): string
    {
        if ($bank > 21) {
            return "spelare";
        }

        return ($bank >= $spelare || $bank === 21) ? "bank" : "spelare";
    }
}
