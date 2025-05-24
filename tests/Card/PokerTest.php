<?php

namespace App\Card;

use App\Card\Card;
use App\Card\Poker;
use PHPUnit\Framework\TestCase;

class PokerTest extends TestCase
{
    private Poker $poker;

    protected function setUp(): void
    {
        $this->poker = new Poker();
    }

    public function testHighCard(): void
    {
        $hand = [
            new Card('♠', '5'),
            new Card('♠', '8'),
            new Card('♠', '3'),
            new Card('♠', '10'),
            new Card('♣', 'A'),
        ];
        $this->assertEquals(14, $this->poker->getHighCard($hand));
        $this->assertFalse($this->poker->isPair($hand));
        $this->assertEquals(14, $this->poker->evaluateHand($hand));
    }

    public function testIsPair(): void
    {
        $hand = [
            new Card('♠', '5'),
            new Card('♣', '5'),
            new Card('♥', '8'),
            new Card('♦', '8'),
            new Card('♠', '2'),
        ];
        $this->assertTrue($this->poker->isPair($hand));
        $this->assertTrue($this->poker->isTwoPair($hand));
        $this->assertEquals(45, $this->poker->evaluateHand($hand));
    }

    public function testIsFlush(): void
    {
        $hand = [
            new Card('♠', '3'),
            new Card('♠', '3'),
            new Card('♠', '3'),
            new Card('♠', '2'),
            new Card('♠', '2'),
        ];
        $this->assertTrue($this->poker->isFlush($hand));
        $this->assertTrue($this->poker->isThree($hand));
        $this->assertTrue($this->poker->isFullHouse($hand));
        $this->assertFalse($this->poker->isStraight($hand));
        $this->assertEquals(105, $this->poker->evaluateHand($hand));
    }

    public function testIsStraight(): void
    {
        $hand = [
            new Card('♠', '5'),
            new Card('♣', '6'),
            new Card('♦', '7'),
            new Card('♠', '8'),
            new Card('♥', '9'),
        ];
        $this->assertTrue($this->poker->isStraight($hand));
        $this->assertFalse($this->poker->isFlush($hand));
        $this->assertEquals(75, $this->poker->evaluateHand($hand));
    }

    public function testIsRoyalFlush(): void
    {
        $hand = [
            new Card('♠', '10'),
            new Card('♠', 'J'),
            new Card('♠', 'Q'),
            new Card('♠', 'K'),
            new Card('♠', 'A'),
        ];
        $this->assertTrue($this->poker->isRoyalFlush($hand));
        $this->assertTrue($this->poker->isStraightFlush($hand));
    }

    public function testIsFour(): void
    {
        $hand = [
            new Card('♠', '10'),
            new Card('♠', '10'),
            new Card('♠', '10'),
            new Card('♠', '10'),
            new Card('♠', 'A'),
        ];
        $this->assertTrue($this->poker->isFour($hand));
        $this->assertEquals(120, $this->poker->evaluateHand($hand));
    }

    public function testScore(): void
    {
        $straightFlush = [
            new Card('♠', '2'),
            new Card('♠', '3'),
            new Card('♠', '4'),
            new Card('♠', '5'),
            new Card('♠', 'A'),
        ];
        $this->assertEquals(135, $this->poker->evaluateHand($straightFlush));
        $this->assertTrue($this->poker->isStraight($straightFlush));
    }
}