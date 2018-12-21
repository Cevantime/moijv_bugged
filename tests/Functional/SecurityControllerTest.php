<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/login');
        $button = $crawler->filter('button[type="submit"]');
        $form = $button->form();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Connexion', $button->text());

        $form['_username'] = "root";
        $form['_password'] = "admin";

        $client->submit($form);

        $client->followRedirect(); // redirection vers la home "/"
        $newPageCrawler = $client->followRedirect(); // redirection vers la home + locale "/fr/home"


        //dump($client->getResponse()->getStatusCode());

        $this->assertContains('Welcome on the home page !', $newPageCrawler->filter('h2')->text());
    }
}
