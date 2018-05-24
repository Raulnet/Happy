<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 16/05/18
 * Time: 23:06.
 */

namespace Happy\Tests\Controller;

use Happy\Entity\Project;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class ProjectControllerTest.
 */
class ProjectControllerTest extends WebTestCase
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
     * @var string
     */
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

    public function testPostProject()
    {
        $project = ['project' => ['id' => $this->uuid, 'name' => 'phpunit project', 'urlDocumentation' => 'http://localhost:3000']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_project');
        $this->client->request('POST', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testGetProject()
    {
        $project = ['project' => ['id' => $this->uuid, 'name' => 'phpunit project', 'urlDocumentation' => 'http://localhost:3000']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_project');
        $this->client->request('POST', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_project', ['id' => 'bad_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok
        $path = $this->router->generate('_happy_get_project', ['id' => $this->uuid]);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditProject()
    {
        $project = ['project' => ['id' => $this->uuid, 'name' => 'phpunit project', 'urlDocumentation' => 'http://localhost:3000']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_project');
        $this->client->request('POST', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_edit_project', ['id' => 'bad_id']);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        // TEST Response 409 conflict Empty body content
        $path = $this->router->generate('_happy_edit_project', ['id' => $this->uuid]);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());

        $project = ['project' => ['id' => $this->uuid, 'name' => 'phpunit project edited', 'urlDocumentation' => 'http://localhost:3000', 'date_update' => 'now']];
        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_edit_project', ['id' => $this->uuid]);
        $this->client->request('PATCH', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->client->request('PATCH', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testRemoveProject()
    {
        $project = ['project' => ['id' => $this->uuid, 'name' => 'phpunit project', 'urlDocumentation' => 'http://localhost:3000']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_project');
        $this->client->request('POST', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_remove_project', ['id' => 'bad_id']);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_remove_project', ['id' => $this->uuid]);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
