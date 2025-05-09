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
        $this->assertSelectorExists('body');
        $this->assertStringContainsString('number', $client->getResponse()->getContent());
    }

    public function testHomeRoute(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Home');
    }

    public function testSessionDelete(): void
    {
        $client = static::createClient();

        $client->request('GET', '/session');

        $client->request('POST', '/session/delete');

        $this->assertResponseRedirects('/session');
        $client->followRedirect();

        $this->assertSelectorExists('.flash-notice');
        $this->assertSelectorTextContains('.flash-notice', 'cleared successfully');
    }

    public function testAboutPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'About');
    }

    public function testReportPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/report');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Redovisning');
    }
}
