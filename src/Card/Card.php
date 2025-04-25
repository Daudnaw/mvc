<?php

namespace App\Card;

/**
 * Class Card
 * 
 * This class represents a card with suit and rank
 */
class Card
{
    /**
     * @var string Cards suits like hearts and clubs
     */
    protected string $suit;

    /**
     * @var string Cards rank like A, J and 2
     */
    protected string $rank;

    /**
     * Creates a new card with arguments suit and rank
     * 
     * @param string $suit cards color
     * @param string $rank cards rank
     */
    public function __construct(string $suit, string $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }

    /**
     * Get cards suit
     * 
     * @return string cards suit
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Get cards rank
     * 
     * @return string cards rank
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * Returns card as a string like [Ahearts]
     * 
     * @return string card representation as a string
     */
    public function getAsString(): string
    {
        return '[' . $this->rank . $this->suit . ']';
    }
}
