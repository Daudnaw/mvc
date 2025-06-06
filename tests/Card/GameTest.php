<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the Game class.
 */
class GameTest extends TestCase
{
    /**
     * Test getRank returns correct numeric value for face and number cards.
     */
    public function testGetRank(): void
    {
        $game = new Game();

        $ace = new Card('hearts', 'A');
        $this->assertEquals(14, $game->getRank($ace));

        $num = new Card('hearts', '7');
        $this->assertEquals(7, $game->getRank($num));
    }

    /**
     * Test winLose logic for different perspectives.
     */
    public function testWinLose(): void
    {
        $game = new Game();

        $this->assertEquals("spelare", $game->winLose(18, 22));
        $this->assertEquals("bank", $game->winLose(18, 20));
        $this->assertEquals("spelare", $game->winLose(20, 19));
        $this->assertEquals("bank", $game->winLose(17, 21));
    }

    /**
     * Test inherited functionality from class Deck.
     */
    public function testGameFuncationality(): void
    {
        $game = new Game();
        $this->assertEquals(52, $game->getCount());

        $card = $game->drawCard();
        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals(51, $game->getCount());
    }

    /**
     * Test gamecards
     */
    public function testGetGameCards(): void
    {
        $card1 = $this->createMock(Card::class);
        $card1->method('getAsString')->willReturn('4s');

        $card2 = $this->createMock(Card::class);
        $card2->method('getAsString')->willReturn('AS');

        $card3 = $this->createMock(Card::class);
        $card3->method('getAsString')->willReturn('KH');

        $cards = [$card1, $card2, $card3];

        $game = new Game();

        $result = $game->getGameCards($cards);

        $this->assertEquals(['4s', 'AS', 'KH'], $result);
    }
}
