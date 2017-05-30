<?php

namespace BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ThemesControllerTest extends WebTestCase
{
    public function testChoosetheme()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/chooseTheme');
    }

}
