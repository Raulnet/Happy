<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 16/05/18
 * Time: 21:33
 */

namespace Happy\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class HealthControllerTest
 *
 * @package Happy\Tests\Controller
 */
class HealthControllerTest extends WebTestCase
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

    public function testHealth()
    {
        $this->client->request('GET', '/api/health');
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('"OK"', $this->client->getResponse()->getContent());
    }
}