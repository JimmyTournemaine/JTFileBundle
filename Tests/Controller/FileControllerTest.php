<?php

namespace JT\FileBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileControllerTest extends WebTestCase
{
    public function testDownload()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/download');
    }

}
