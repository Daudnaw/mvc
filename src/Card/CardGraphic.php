<?php

namespace App\Card;

class CardGraphic extends Card
{
    protected $suits = [
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
