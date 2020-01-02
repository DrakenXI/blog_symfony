<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CrudController extends WebTestCase {
    /**
    * Protected routes.
    * @dataProvider provideUrls
    */
    public function testPageIsSuccessful($url)
    {
       $client = self::createClient();
       $client->request('GET', $url);
       // redirection : user doit etre connecte pour acceder a cette page
       $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function provideUrls()
    {
       return [
           ['/new'], /* OK */
           ['/edit/cookies'], /* OK */
           ['/delete/cookies'] /* OK */
       ];
    }

}
