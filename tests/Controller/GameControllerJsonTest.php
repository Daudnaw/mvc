<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerJsonTest extends WebTestCase
{
    public function testGameApi(): void
    {
        $client = static::createClient();

        $session = $client->getContainer()->get('session.factory')->createSession();
        $session->start();
        $client->getContainer()->set('session', $session);

        $client->request('GET', '/api/game');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('drawCard', $data);
        $this->assertArrayHasKey('playerTotal', $data);
        $this->assertArrayHasKey('bankCards', $data);
        $this->assertArrayHasKey('bankTotal', $data);
        $this->assertArrayHasKey('winner', $data);

        $this->assertEquals('Game not started yat', $data['drawCard']);
    }
}
