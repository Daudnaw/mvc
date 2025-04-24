<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Test object creation and string representation using symbols.
     */
    public function testCreateCardGraphic()
    {
        $card = new CardGraphic('hearts', 'Q');
        $this->assertInstanceOf("\App\Card\CardGraphic", $card);
        $this->assertInstanceOf("\App\Card\Card", $card); // still Card?

        $symbol = $card->getSuitSymbol();
        $this->assertEquals('♥', $symbol);

        $str = $card->getAsString();
        $this->assertEquals('[Q♥]', $str);
        $this->assertStringContainsString('♥', $str);
    }

}
