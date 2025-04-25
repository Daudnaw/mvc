<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateCard(): void
    {
        $card = new Card('clubs', '5');
        $this->assertInstanceOf("\App\Card\Card", $card);

        $res = $card->getAsString();
        $this->assertNotEmpty($res);

        $rank = $card->getRank();
        $exp = 5;
        $this->assertEquals($rank, $exp);

        $suit = $card->getSuit();
        $this->assertEquals($suit, 'clubs');
    }
}
