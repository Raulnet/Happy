<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 21:15
 */

namespace Happy\Tests\Controller;

use Happy\Entity\User;
use Happy\Tests\AbstractTestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class UserControllerTest
 *
 * @package Happy\Tests\Controller
 */
class UserControllerTest extends AbstractTestCase
{
    /** @var User $user */
    private $user;
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
        $this->user = new User();
        $uuid = Uuid::uuid4();
        $this->user->setId($uuid->toString());
    }

    public function testGetUsers()
    {
        // TEST Reponse 200 Ok
        $path = $this->router->generate('_happy_get_users');
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testGetUserById()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_user', ['id' => 'bad_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok
        $path = $this->router->generate('_happy_get_user', ['id' => $this->user->getId()]);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPostProject()
    {
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_user');
        $this->client->request('POST', $path);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testEditProject()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_edit_user', ['id' => 'bad_id']);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_edit_user', ['id' => $this->user->getId()]);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testRemoveProject()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_remove_user', ['id' => 'bad_id']);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_remove_user', ['id' => $this->user->getId()]);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}