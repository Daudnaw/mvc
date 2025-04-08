<?php

namespace App\Card;

use App\Card\Card;

class Deck
{
    private $cards = [];

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

    public function shuffleDeck()
    {
        $shuffledDeck = $this->cards;
        shuffle($shuffledDeck);
        return $shuffledDeck;
    }

    public function drawCard()
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

    public function getCount()
    {
        return count($this->cards);
    }

    public function drawCards(int $numCards)
    {
        $drawnCards = [];

        $numCards = min($numCards, count($this->cards));

        for ($i = 0; $i < $numCards; $i++) {
            $drawnCards[] = $this->drawCard();
        }

        return $drawnCards;
    }

    public function getString($deck = null)
    {
        $deckToDisplay = $deck ? $deck : $this->cards;

        $values = [];

        foreach ($deckToDisplay as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }
}
