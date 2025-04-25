<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck.
 */
class DeckTest extends TestCase
{
    /**
     * Test deck creation and card count.
     */
    public function testCreateDeck()
    {
        $deck = new Deck();
        $this->assertEquals(52, $deck->getCount());

        $cards = $deck->getString();
        $this->assertCount(52, $cards);
    }

    /**
     * Test deck shuffling.
     */
    public function testShuffleDeck()
    {
        $deck = new Deck();
        $original = $deck->getString();
        $shuffled = $deck->shuffleDeck();

        $this->assertCount(52, $shuffled);
        $arrayShuffled = $deck->getString($shuffled);
        $this->assertNotEquals($original, $arrayShuffled);
    }

    /**
     * Test drawing one crd.
     */
    public function testDrawCard()
    {
        $deck = new Deck();
        $countBefore = $deck->getCount();

        $card = $deck->drawCard();
        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals($countBefore - 1, $deck->getCount());
    }

    /**
     * Test drawing multiple cards.
     */
    public function testDrawCards()
    {
        $deck = new Deck();
        $drawn = $deck->drawCards(5);

        $this->assertCount(5, $drawn);
        $this->assertEquals(47, $deck->getCount());
    }

    /**
     * Test getString on a deck and the form.
     */
    public function testGetStringDeck()
    {
        $deck = new Deck();
        $subset = $deck->drawCards(3);
        $strings = $deck->getString($subset);

        $this->assertCount(3, $strings);
        foreach ($strings as $str) {
            $this->assertStringStartsWith('[', $str);
            $this->assertStringEndsWith(']', $str);
        }
    }

    /**
     * Test drawing from empty deck returns null.
     */
    public function testDrawFromEmptyDeck()
    {
        $deck = new Deck();
        $deck->drawCards(52);

        $this->assertEquals(0, $deck->getCount());
        $this->assertNull($deck->drawCard());
    }
}
