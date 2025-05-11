<?php

namespace App\Tests\Controller;

use App\Controller\ShowCardControllerJson;
use App\Card\Deck;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowCardControllerJsonTest extends WebTestCase
{
    public function testJsonDeck(): void
    {
        // Create a mock for the Deck class
        $mockDeck = $this->createMock(Deck::class);

        $mockDeck->method('getString')->willReturn(['[A♥]']);
        $mockDeck->method('getCount')->willReturn(52);

        $controller = new ShowCardControllerJson();

        $response = $controller->jsonDeck();

        $this->assertInstanceOf(JsonResponse::class, $response);

        $data = json_decode($response->getContent(), true);

        $this->assertEquals('[A♥]', $data['newDeck'][0]);
        $this->assertEquals(52, $data['count']);
    }

}
