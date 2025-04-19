<?php

namespace App\Card;

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

    public function __construct(string $suit, string $rank)
    {
        parent::__construct($suit, $rank);
    }

    public function getSuitSymbol(): string
    {
        return $this->suits[$this->suit] ?? '';
    }

    public function getAsString(): string
    {
        return '[' . $this->rank . $this->getSuitSymbol() . ']';
    }
}
