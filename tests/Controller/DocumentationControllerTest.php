<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 21:27.
 */

namespace Happy\Tests\Controller;

use Happy\Entity\Project;
use Happy\Tests\AbstractTestCase;
use Happy\Entity\Documentation;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class DocumentationControllerTest.
 */
class DocumentationControllerTest extends AbstractTestCase
{
    /** @var Documentation $documentation */
    private $documentation;
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
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
        $this->documentation = new Documentation();
        $uuid = Uuid::uuid4();
        $this->documentation->setId($uuid->toString());
        $this->project = new Project();
        $this->project->setId($uuid->toString());
    }

    public function testGetDocumentations()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_documentations', ['projectId' => 'bad_project_id', 'id' => 'bad_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok
        $path = $this->router->generate('_happy_get_documentations', ['projectId' => $this->project->getId()]);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testGetDocumentation()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_documentation', ['projectId' => 'bad_project_id', 'id' => 'bad_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok
        $path = $this->router->generate('_happy_get_documentation', ['projectId' => $this->project->getId(), 'id' => $this->documentation->getId()]);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testPostDocumentation()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_post_documentation',['projectId' => 'bad_project_id']);
        $this->client->request('POST', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_documentation',['projectId' => $this->project->getId()]);
        $this->client->request('POST', $path);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testEditProject()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_edit_documentation',  ['projectId' => 'bad_project_id', 'id' => 'bad_id']);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_edit_documentation', ['projectId' => $this->project->getId(), 'id' => $this->documentation->getId()]);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testRemoveProject()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_remove_documentation', ['projectId' => 'bad_project_id', 'id' => 'bad_id']);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_remove_documentation', ['projectId' => $this->project->getId(), 'id' => $this->documentation->getId()]);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}