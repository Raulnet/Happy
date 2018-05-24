<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 13:08.
 */

namespace Happy\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class HomeControllerTest.
 */
class HomeControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;

    /**
     * init Client.
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testHome()
    {
        $this->client->request('GET', '/api/');
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
