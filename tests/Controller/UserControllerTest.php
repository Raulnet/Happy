<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 21:15.
 */

namespace Happy\Tests\Controller;

use Happy\Tests\AbstractTestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends AbstractTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;
    /**
     * @var Router
     */
    private $router;

    private $uuid;

    /**
     * init Client.
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
        $uuid = Uuid::uuid4();
        $this->uuid = $uuid->toString();
    }

    public function testPostUser()
    {
        $content = ['id' => $this->uuid, 'roles' => ['ROLE_USER', 'ROLE_BOB']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_user');
        $this->client->request('POST', $path, [], [], [], json_encode($content));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        // TEST Reponse 409 empty content
        $path = $this->router->generate('_happy_post_user');
        $this->client->request('POST', $path, [], [], [], null);
        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());

        // TEST Exception
        $path = $this->router->generate('_happy_post_user');
        $this->client->request('POST', $path, [], [], [], json_encode($content));
        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());
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
        $content = ['id' => $this->uuid, 'roles' => ['ROLE_USER', 'ROLE_BOB']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_user');
        $this->client->request('POST', $path, [], [], [], json_encode($content));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_user', ['id' => 'bad_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok
        $path = $this->router->generate('_happy_get_user', ['id' => $this->uuid]);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUser()
    {
        $content = ['id' => $this->uuid, 'roles' => ['ROLE_USER', 'ROLE_BOB'], 'projects' => []];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_user');
        $this->client->request('POST', $path, [], [], [], json_encode($content));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $content = ['roles' => ['ROLE_USER', 'ROLE_ADMIN']];
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_edit_user', ['id' => 'bad_id']);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_edit_user', ['id' => $this->uuid]);
        $this->client->request('PATCH', $path, [], [], [], json_encode($content));
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path, [], [], [], json_encode($content));
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testRemoveUser()
    {
        $content = ['id' => $this->uuid, 'roles' => ['ROLE_USER', 'ROLE_BOB']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_user');
        $this->client->request('POST', $path, [], [], [], json_encode($content));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_remove_user', ['id' => 'bad_id']);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_remove_user', ['id' => $this->uuid]);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAddProject()
    {
        $content = ['id' => $this->uuid, 'roles' => ['ROLE_USER', 'ROLE_BOB']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_user');
        $this->client->request('POST', $path, [], [], [], json_encode($content));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $project = ['id' => $this->uuid, 'name' => 'phpunit project', 'urlDocumentation' => 'http://localhost:3000'];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_project');
        $this->client->request('POST', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok added
        $path = $this->router->generate('_happy_user_add_project', ['userId' => $this->uuid, 'projectId' => $this->uuid]);
        $this->client->request('PATCH', $path, [], [], []);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path, [], [], []);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 404 not found
        $path = $this->router->generate('_happy_user_add_project', ['userId' => 'wrong id', 'projectId' => $this->uuid]);
        $this->client->request('PATCH', $path, [], [], []);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testRemoveProject()
    {
        $content = ['id' => $this->uuid, 'roles' => ['ROLE_USER', 'ROLE_BOB']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_user');
        $this->client->request('POST', $path, [], [], [], json_encode($content));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $project = ['id' => $this->uuid, 'name' => 'phpunit project', 'urlDocumentation' => 'http://localhost:3000'];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_project');
        $this->client->request('POST', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok removed
        $path = $this->router->generate('_happy_user_remove_project', ['userId' => $this->uuid, 'projectId' => $this->uuid]);
        $this->client->request('DELETE', $path, [], [], []);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 404 not found
        $path = $this->router->generate('_happy_user_remove_project', ['userId' => 'WRONG_ID', 'projectId' => $this->uuid]);
        $this->client->request('DELETE', $path, [], [], []);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }
}
