<?php

namespace App\Card;

/**
 * Class CardGraphic
 * 
 * This class inherits from the Card class.
 * This class gives a card symbolic representation.
 */
class CardGraphic extends Card
{
    /**
     * @var string[] Array of string keys and string values for suits
     */
    protected array $suits = [
        'hearts' => '♥',
        'spades' => '♠',
        'diamonds' => '♦',
        'clubs' => '♣',
    ];

    /**
     * Create a card with symbolic suit '♥' instead of string 'hearts'.
     * 
     * @param string $suit cards suit ex. 'clubs'.
     * @param string $rank cards rank ex. 'A'.
     */
    public function __construct(string $suit, string $rank)
    {
        parent::__construct($suit, $rank);
    }

    /**
     * Gets the symbol for a card suit.
     * 
     * @return string Graphic symbol for a suit.
     */
    public function getSuitSymbol(): string
    {
        return $this->suits[$this->suit] ?? '';
    }

    /**
     * Returns a card with graphicsymbol ex. '[A♥]'.
     * 
     * @return string Card as string in graphic form
     */
    public function getAsString(): string
    {
        return '[' . $this->rank . $this->getSuitSymbol() . ']';
    }
}
