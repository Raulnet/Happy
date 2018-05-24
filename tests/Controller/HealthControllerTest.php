<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 16/05/18
 * Time: 21:33.
 */

namespace Happy\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class HealthControllerTest.
 */
class HealthControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;
    /**
     * @var Router
     */
    private $router;

    /**
     * init Client.
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
    }

    public function testHealth()
    {
        $path = $this->router->generate('_happy_health');
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('"OK"', $this->client->getResponse()->getContent());
    }
}
