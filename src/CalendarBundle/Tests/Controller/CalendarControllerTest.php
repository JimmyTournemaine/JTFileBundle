<?php

namespace CalendarBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarControllerTest extends WebTestCase
{
    public function testCalendar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/calendar');
    }

}
