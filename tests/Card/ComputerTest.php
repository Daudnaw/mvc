<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Computer;
use App\Card\Poker;
use App\Card\Deck;
use App\Card\Card;

class ComputerTest extends TestCase
{
    private Poker $poker;
    private Deck $deck;

    protected function setUp(): void
    {
        $this->poker = $this->createMock(Poker::class);
        $this->deck = $this->createMock(Deck::class);
    }

    public function testPlayDrawsCorrectNumberOfCards(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $hand = [
            new Card('♠', '2'),
            new Card('♦', '5'),
            new Card('♣', '8'),
            new Card('♥', '9'),
            new Card('♠', 'J')
        ];

        $this->poker->method('getRank')->willReturnMap([
            [$hand[0], 2],
            [$hand[1], 5],
            [$hand[2], 8],
            [$hand[3], 9],
            [$hand[4], 11],
        ]);

        $this->poker->method('getRankFreq')->willReturn([]);
        $this->poker->method('isRoyalFlush')->willReturn(false);
        $this->poker->method('isStraightFlush')->willReturn(false);
        $this->poker->method('isFlush')->willReturn(false);
        $this->poker->method('isStraight')->willReturn(false);
        $this->poker->method('isFour')->willReturn(false);
        $this->poker->method('isFullHouse')->willReturn(false);
        $this->poker->method('isThree')->willReturn(false);
        $this->poker->method('isTwoPair')->willReturn(false);
        $this->poker->method('isPair')->willReturn(false);

        $this->deck->method('drawCards')->with(5)->willReturn([
            new Card('♠', 'Q'),
            new Card('♠', '3'),
            new Card('♠', '6'),
            new Card('♠', '7'),
            new Card('♠', 'K'),
        ]);

        $newHand = $computer->play($hand);
        $this->assertCount(5, $newHand);
    }

    public function testKeepHighCardsOnly(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $hand = [
            new Card('♠', 'A'),
            new Card('♦', 'K'),
            new Card('♣', '2'),
            new Card('♥', '5'),
            new Card('♠', '7')
        ];

        $this->poker->method('getRank')->willReturnMap([
            [$hand[0], 14],
            [$hand[1], 13],
            [$hand[2], 2],
            [$hand[3], 5],
            [$hand[4], 7],
        ]);

        $kept = $computer->keepHighCards($hand);
        $this->assertCount(2, $kept);
        $this->assertEquals('A', $kept[0]->getRank());
        $this->assertEquals('K', $kept[1]->getRank());
    }

    public function testGroupRanks(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $hand = [
            new Card('♠', 'A'),
            new Card('♦', 'A'),
            new Card('♣', 'K'),
            new Card('♥', 'K'),
            new Card('♠', 'Q')
        ];

        $grouped = $computer->groupRanks($hand);
        $this->assertCount(3, $grouped);
        $this->assertCount(2, $grouped['A']);
        $this->assertCount(2, $grouped['K']);
        $this->assertCount(1, $grouped['Q']);
    }

    public function testGetCardsByRank(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $cardA1 = new Card('♠', 'A');
        $cardA2 = new Card('♦', 'A');
        $cardA3 = new Card('♣', 'A');
        $cardK = new Card('♥', 'K');
        $cardQ = new Card('♠', 'Q');

        $groups = [
            'A' => [$cardA1, $cardA2, $cardA3],
            'K' => [$cardK],
            'Q' => [$cardQ]
        ];

        $freq = [
            'A' => 3,
            'K' => 1,
            'Q' => 1
        ];

        $kept = $computer->getCardsByRank($groups, $freq, 3);
        $this->assertEquals([$cardA1, $cardA2, $cardA3], $kept);
    }

    public function testGetCardsByRankReturnsEmptyArrayWhenNoMatch(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $cardA = new Card('♠', 'A');
        $cardK = new Card('♦', 'K');
        $cardQ = new Card('♣', 'Q');

        $groups = [
            'A' => [$cardA],
            'K' => [$cardK],
            'Q' => [$cardQ]
        ];

        $freq = [
            'A' => 1,
            'K' => 1,
            'Q' => 1
        ];

        $result = $computer->getCardsByRank($groups, $freq, 2);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetTwoPairs(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $cardA1 = new Card('♠', 'A');
        $cardA2 = new Card('♦', 'A');
        $cardK1 = new Card('♣', 'K');
        $cardK2 = new Card('♥', 'K');
        $cardQ = new Card('♠', 'Q');

        $groups = [
            'A' => [$cardA1, $cardA2],
            'K' => [$cardK1, $cardK2],
            'Q' => [$cardQ]
        ];

        $freq = [
            'A' => 2,
            'K' => 2,
            'Q' => 1
        ];

        $kept = $computer->getTwoPairs($groups, $freq);
        $this->assertEquals([$cardA1, $cardA2, $cardK1, $cardK2], $kept);
    }

    public function testKeepFullHandReturnsTrueForStraight(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $hand = [
            new Card('♠', '2'),
            new Card('♠', '3'),
            new Card('♠', '4'),
            new Card('♠', '5'),
            new Card('♠', '6')
        ];

        $this->poker->method('isRoyalFlush')->willReturn(false);
        $this->poker->method('isStraightFlush')->willReturn(false);
        $this->poker->method('isFlush')->willReturn(false);
        $this->poker->method('isStraight')->willReturn(true);

        $this->assertTrue($computer->keepFullHand($hand));
    }

    public function testKeepCardsReturnsFourOfAKind(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $cards = [
            new Card('♠', '9'),
            new Card('♥', '9'),
            new Card('♦', '9'),
            new Card('♣', '9'),
            new Card('♠', 'Q')
        ];

        $this->poker->method('isRoyalFlush')->willReturn(false);
        $this->poker->method('isStraightFlush')->willReturn(false);
        $this->poker->method('isFlush')->willReturn(false);
        $this->poker->method('isStraight')->willReturn(false);
        $this->poker->method('isFour')->willReturn(true);
        $this->poker->method('getRankFreq')->willReturn(['9' => 4, 'Q' => 1]);

        $result = $computer->keepCards($cards);

        $this->assertCount(4, $result);
        $this->assertEquals('9', $result[0]->getRank());
    }

    public function testKeepCardsReturnsFullHouse(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $cards = [
            new Card('♠', '4'),
            new Card('♥', '4'),
            new Card('♦', '4'),
            new Card('♣', 'J'),
            new Card('♠', 'J')
        ];

        $this->poker->method('isRoyalFlush')->willReturn(false);
        $this->poker->method('isStraightFlush')->willReturn(false);
        $this->poker->method('isFlush')->willReturn(false);
        $this->poker->method('isStraight')->willReturn(false);
        $this->poker->method('isFour')->willReturn(false);
        $this->poker->method('isFullHouse')->willReturn(true);

        $result = $computer->keepCards($cards);

        $this->assertSame($cards, $result);
    }

    public function testKeepCardsReturnsThreeOfAKind(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $cards = [
            new Card('♠', '6'),
            new Card('♥', '6'),
            new Card('♦', '6'),
            new Card('♣', '8'),
            new Card('♠', 'J')
        ];

        $this->poker->method('isThree')->willReturn(true);
        $this->poker->method('getRankFreq')->willReturn(['6' => 3, '8' => 1, 'J' => 1]);

        $result = $computer->keepCards($cards);

        $this->assertCount(3, $result);
        $this->assertEquals('6', $result[0]->getRank());
    }

    public function testKeepCardsReturnsTwoPairs(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $cards = [
            new Card('♠', '10'),
            new Card('♥', '10'),
            new Card('♦', '7'),
            new Card('♣', '7'),
            new Card('♠', 'Q')
        ];

        $this->poker->method('isTwoPair')->willReturn(true);
        $this->poker->method('getRankFreq')->willReturn(['10' => 2, '7' => 2, 'Q' => 1]);

        $result = $computer->keepCards($cards);

        $this->assertCount(4, $result);
        $this->assertEquals('10', $result[0]->getRank());
        $this->assertEquals('7', $result[2]->getRank());
    }

    public function testKeepCardsReturnsPair(): void
    {
        $computer = new Computer($this->poker, $this->deck);

        $cards = [
            new Card('♠', '5'),
            new Card('♥', '5'),
            new Card('♦', '9'),
            new Card('♣', '2'),
            new Card('♠', 'J')
        ];

        $this->poker->method('isPair')->willReturn(true);
        $this->poker->method('getRankFreq')->willReturn(['5' => 2, '9' => 1, '2' => 1, 'J' => 1]);

        $result = $computer->keepCards($cards);

        $this->assertCount(2, $result);
        $this->assertEquals('5', $result[0]->getRank());
    }
}
