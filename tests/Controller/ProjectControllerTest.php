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
    /** @var Project $project */
    private $project;
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
        $this->client  = static::createClient();
        $this->router  = $this->client->getContainer()->get('router');
        $this->project = new Project();
        $uuid          = Uuid::uuid4();
        $this->project->setId($uuid->toString());
    }

    public function testGetProject()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_project', ['id' => 'bad_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok
        $path = $this->router->generate('_happy_get_project', ['id' => $this->project->getId()]);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPostProject()
    {
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_project');
        $this->client->request('POST', $path);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

    }

    public function testEditProject()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_edit_project', ['id' => 'bad_id']);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_edit_project', ['id' => $this->project->getId()]);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_ACCEPTED, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_ACCEPTED, $this->client->getResponse()->getStatusCode());
    }

    public function testRemoveProject()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_remove_project', ['id' => 'bad_id']);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_remove_project', ['id' => $this->project->getId()]);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_ACCEPTED, $this->client->getResponse()->getStatusCode());
    }

    public function testRestoreProject()
    {
        $path = $this->router->generate('_happy_restore_project', ['id' => $this->project->getId()]);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }
}