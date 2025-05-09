<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllerTwigTest extends WebTestCase
{
    /**
     * Test Lucky
     */
    public function testLuckyNumber(): void
    {
        $client = static::createClient();
        $client->request('GET', '/lucky');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
        $this->assertStringContainsString('number', $client->getResponse()->getContent());
    }

}
