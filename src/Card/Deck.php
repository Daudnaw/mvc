<?php

namespace App\Card;

use App\Card\Card;

class Deck
{
    private array $cards = [];

    public function __construct()
    {
        $suits = ['hearts', 'spades', 'diamonds', 'clubs'];
        $ranks = ['A','2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $this->cards[] = new CardGraphic($suit, $rank);
            }
        }
    }

    public function shuffleDeck(): array
    {
        $shuffledDeck = $this->cards;
        shuffle($shuffledDeck);
        return $shuffledDeck;
    }

    public function drawCard(): ?Card
    {
        if (empty($this->cards)) {
            return null;
        }

        $randIndex = array_rand($this->cards);
        $randomCard = $this->cards[$randIndex];
        unset($this->cards[$randIndex]);

        $this->cards = array_values($this->cards);

        return $randomCard;
    }

    public function getCount(): int
    {
        return count($this->cards);
    }

    public function drawCards(int $numCards): array
    {
        $drawnCards = [];

        $numCards = min($numCards, count($this->cards));

        for ($i = 0; $i < $numCards; $i++) {
            $drawnCards[] = $this->drawCard();
        }

        return $drawnCards;
    }

    /**
    * @param array|null $deck
    * @return string[] Array of strings representing the cards
    */
    public function getString($deck = null): array
    {
        $deckToDisplay = $deck ? $deck : $this->cards;

        $values = [];

        foreach ($deckToDisplay as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }
}
