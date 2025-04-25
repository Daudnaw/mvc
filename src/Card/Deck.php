<?php

namespace App\Card;

use App\Card\Card;

/**
 * Class Deck
 *
 * This is a composition of class CardGraphics 52  objects
 */
class Deck
{
    /**
     * @var CardGraphic[] Array with all cards
     */
    private array $cards = [];

    /**
     * Creates a new deck of cards with 52 cards, no jokers
     */
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

    /**
     * Mix upp the deck and return a new mixed array av cards.
     *
     * @return CardGraphic[] mixed cards
     */
    public function shuffleDeck(): array
    {
        $shuffledDeck = $this->cards;
        shuffle($shuffledDeck);
        return $shuffledDeck;
    }

    /**
     * Take away a random card from the deck
     *
     * @return CardGraphic|null returns a card if deck is not finished
     */
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

    /**
     * Reutrns number of card remaining in the deck
     *
     * @return int Number of cards in the deck
     */
    public function getCount(): int
    {
        return count($this->cards);
    }

    /**
     * Take away give number of cards from the deck
     *
     * @param int $numCards Number of cards to be drawn.
     * @return CardGraphic[] Array with given number of cards.
     */
    public function drawCards(int $numCards): array
    {
        $drawnCards = [];

        $numCards = min($numCards, count($this->cards));

        for ($i = 0; $i < $numCards; $i++) {
            $card = $this->drawCard();

            if ($card === null) {
                break;
            }

            $drawnCards[] = $card;
        }
        return $drawnCards;
    }

    /**
     * Returns cards as strings
     *
    * @param CardGraphic[]|null $deck cards to be stringed if that is a verb.
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
