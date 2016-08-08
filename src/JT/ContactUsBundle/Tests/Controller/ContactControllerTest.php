<?php

namespace JT\ContactUsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testDisplayform()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

}
