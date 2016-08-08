<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArchiveControllerTest extends WebTestCase
{
    public function testRetrieve()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/retrieve');
    }

}
