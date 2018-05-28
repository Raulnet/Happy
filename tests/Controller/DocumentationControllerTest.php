<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 21:27.
 */

namespace Happy\Tests\Controller;

use Happy\Controller\DocumentationController;
use Happy\Entity\Documentation;
use Happy\Entity\Project;
use Happy\Tests\AbstractTestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class DocumentationControllerTest.
 */
class DocumentationControllerTest extends AbstractTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;
    /**
     * @var Router
     */
    private $router;

    private $documentationId;

    private $projectId;

    private $postDocumentations = [];

    private $postProjects = [];

    /**
     * init Client.
     */
    public function setUp()
    {
        $this->client          = static::createClient();
        $this->router          = $this->client->getContainer()->get('router');
        $uuid                  = Uuid::uuid4();
        $this->documentationId = $uuid->toString();
        $uuid                  = Uuid::uuid4();
        $this->projectId       = $uuid->toString();
    }

    public function testPostDocumentation()
    {

        $projectId = $this->projectId;

        $project = ['id' => $projectId, 'name' => 'phpunit project', 'urlDocumentation' => 'http://localhost:3000'];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_project');
        $this->client->request('POST', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $path = $this->router->generate('_happy_get_projects');
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->postProjects = json_decode($this->client->getResponse()->getContent(), true);

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_post_documentation', ['id' => 'bad_project_id']);
        $this->client->request('POST', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $documentation = ['info' => ['version' => '0.0.0']];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_documentation', ['id' => $projectId]);
        $this->client->request('POST', $path, [], [], [], json_encode($documentation));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $documentation = ['info' => ['version' => '0.0.2']];
        // TEST Reponse 201 Created/update
        $path = $this->router->generate('_happy_post_documentation', ['id' => $projectId]);
        $this->client->request('POST', $path, [], [], [], json_encode($documentation));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

    }

    public function testGetDocumentations()
    {
        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_documentations', ['id' => 'bad_project_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());


        $this->testPostDocumentation();

        $path = $this->router->generate('_happy_get_documentations', ['id' => $this->projectId]);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->postDocumentations = json_decode($this->client->getResponse()->getContent(), true);
    }

    public function testGetDocumentation()
    {
        $this->testGetDocumentations();

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_documentation',
            ['projectId' => 'bad_project_id', 'documentationId' => 'bad_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok
        foreach ($this->postDocumentations as $documentation) {
            $path = $this->router->generate('_happy_get_documentation',
                ['projectId' => $documentation['project']['id'], 'documentationId' => $documentation['id']]);
            $this->client->request('GET', $path);
            $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testEditDocumentation()
    {

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_edit_documentation',
            ['projectId' => 'bad_project_id', 'documentationId' => 'bad_id']);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());


        $this->testGetDocumentations();
        // TEST Response 200 Accepted
        foreach ($this->postDocumentations as $index => $documentation) {
            $swaggerDoc = ['version' => '0.0.2.'.$index];
            $path = $this->router->generate('_happy_edit_documentation',
                ['projectId' => $documentation['project']['id'], 'documentationId' => $documentation['id']]);
            $this->client->request('PATCH', $path, [], [], [], json_encode($swaggerDoc));
            $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
            $this->client->request('PUT', $path, [], [], [], json_encode($swaggerDoc));
            $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testRemoveDocumentation()
    {

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_remove_documentation',
            ['projectId' => 'bad_project_id', 'documentationId' => 'bad_id']);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $this->testGetDocumentations();
        // TEST Response 200 Accepted
        foreach ($this->postDocumentations as $documentation) {
            $path = $this->router->generate('_happy_remove_documentation',
                ['projectId' => $documentation['project']['id'], 'documentationId' => $documentation['id']]);
            $this->client->request('DELETE', $path);
            $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testSupport()
    {
        $controller    = $this->getMockBuilder(DocumentationController::class)->disableOriginalConstructor()->getMock();
        $documentation = new Documentation();
        $project       = new Project();
        $project->setId('first');
        $project2 = new Project();
        $project2->setId('second');
        $documentation->setProject($project);
        $this->expectException(HttpException::class);
        $this->invokeMethod($controller, 'support', [$project2, $documentation]);
    }
}
