<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllerTwigTest extends WebTestCase
{
    public function testLuckyNumber(): void
    {
        $client = static::createClient();
        $client->request('GET', '/lucky');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body'); // Assumes there's a body tag
        $this->assertStringContainsString('number', $client->getResponse()->getContent());
    }

    public function testHomeRoute(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Home'); // Adjust to actual content
    }

    public function testSessionDelete(): void
    {
        $client = static::createClient();

        // First: start the session
        $client->request('GET', '/session');

        // Then: clear it
        $client->request('POST', '/session/delete');

        $this->assertResponseRedirects('/session');
        $client->followRedirect();

        $this->assertSelectorExists('.flash-notice');
        $this->assertSelectorTextContains('.flash-notice', 'cleared successfully');
    }
}
