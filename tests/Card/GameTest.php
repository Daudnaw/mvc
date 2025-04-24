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
    public function testGetRank()
    {
        $game = new Game();

        $ace = new Card('hearts', 'A');
        $this->assertEquals(14, $game->getRank($ace));

        $king = new Card('hearts', 'K');
        $this->assertEquals(13, $game->getRank($king));

        $queen = new Card('hearts', 'Q');
        $this->assertEquals(12, $game->getRank($queen));

        $jack = new Card('hearts', 'J');
        $this->assertEquals(11, $game->getRank($jack));

        $num = new Card('hearts', '7');
        $this->assertEquals(7, $game->getRank($num));
    }

    /**
     * Test winLose logic for various outcomes.
     */
    public function testWinLose()
    {
        $this->assertEquals("spelare", Game::winLose(18, 22));
        $this->assertEquals("bank", Game::winLose(18, 20));
        $this->assertEquals("spelare", Game::winLose(20, 19));
        $this->assertEquals("bank", Game::winLose(21, 21));
        $this->assertEquals("bank", Game::winLose(17, 21));
    }

    /**
     * Test inherited functionality from Deck class.
     */
    public function testDeckFunctionalityInGame()
    {
        $game = new Game();
        $this->assertEquals(52, $game->getCount());

        $card = $game->drawCard();
        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals(51, $game->getCount());
    }
}
