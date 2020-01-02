<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase {

    /**
    * Basic routes.
    * @dataProvider provideUrls
    */
    public function testPageIsSuccessful($url)
    {
       $client = self::createClient();
       $client->request('GET', $url);
       // redirection : user doit etre connecte pour acceder a cette page
       $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function provideUrls()
    {
       return [
           ['/'], /* OK */
           ['/login'], /* OK */
           ['/register'] /* FAILURE */
       ];
    }

    /**
     * Acces a /post/url securise.
     * -> OK
     */
    public function testPostPage()
    {
        $client = static::createClient();
        $client->request('GET', '/post/cookies');
        // redirection : user doit etre connecte pour acceder a cette page
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion de l'admin.
     * TODO : fix unknown $crawler problem.
     */
    public function testAdminConnection()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $form = $crawler->selectButton('submit')->form();

        // set some values
        $form['login'] = 'admin';
        $form['password'] = 'wnpbGx9F5NaS4S8';

        // submit the form
        $crawler = $client->submit($form);

        // requete doit etre un succes
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}
