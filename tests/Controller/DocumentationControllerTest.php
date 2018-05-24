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

    /**
     * init Client.
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
        $uuid = Uuid::uuid4();
        $this->documentationId = $uuid->toString();
        $uuid = Uuid::uuid4();
        $this->projectId = $uuid->toString();
    }

    public function testPostDocumentation($projectId = null, $documentId = null)
    {
        if (empty($projectId)) {
            $uuid = Uuid::uuid4();
            $projectId = $uuid->toString();
            $documentId = $uuid->toString();
        }
        $project = ['id' => $projectId, 'name' => 'phpunit project', 'urlDocumentation' => 'http://localhost:3000'];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_project');
        $this->client->request('POST', $path, [], [], [], json_encode($project));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_post_documentation', ['id' => 'bad_project_id']);
        $this->client->request('POST', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $documentation = ['id' => $documentId, 'version' => '0.0.0', 'path' => '/tmp'];
        // TEST Reponse 201 Created
        $path = $this->router->generate('_happy_post_documentation', ['id' => $projectId]);
        $this->client->request('POST', $path, [], [], [], json_encode($documentation));
        $this->assertEquals(JsonResponse::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testGetDocumentations()
    {
        $this->testPostDocumentation($this->projectId, $this->documentationId);

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_documentations', ['id' => 'bad_project_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok
        $path = $this->router->generate('_happy_get_documentations', ['id' => $this->projectId]);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testGetDocumentation()
    {
        $this->testPostDocumentation($this->projectId, $this->documentationId);

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_get_documentation', ['projectId' => 'bad_project_id', 'documentationId' => 'bad_id']);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Reponse 200 Ok
        $path = $this->router->generate('_happy_get_documentation', ['projectId' => $this->projectId, 'documentationId' => $this->documentationId]);
        $this->client->request('GET', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditDocumentation()
    {
        $this->testPostDocumentation($this->projectId, $this->documentationId);

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_edit_documentation', ['projectId' => 'bad_project_id', 'documentationId' => 'bad_id']);
        $this->client->request('PATCH', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $documentation = ['id' => $this->documentationId, 'version' => '0.0.0', 'path' => '/tmp'];
        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_edit_documentation', ['projectId' => $this->projectId, 'documentationId' => $this->documentationId]);
        $this->client->request('PATCH', $path, [], [], [], json_encode($documentation));
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->client->request('PUT', $path, [], [], [], json_encode($documentation));
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testRemoveDocumentation()
    {
        $this->testPostDocumentation($this->projectId, $this->documentationId);

        // TEST Reponse 404 Not found
        $path = $this->router->generate('_happy_remove_documentation', ['projectId' => 'bad_project_id', 'documentationId' => 'bad_id']);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        // TEST Response 201 Accepted
        $path = $this->router->generate('_happy_remove_documentation', ['projectId' => $this->projectId, 'documentationId' => $this->documentationId]);
        $this->client->request('DELETE', $path);
        $this->assertEquals(JsonResponse::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSupport()
    {
        $controller = $this->getMockBuilder(DocumentationController::class)->disableOriginalConstructor()->getMock();
        $documentation = new Documentation();
        $project = new Project();
        $project->setId('first');
        $project2 = new Project();
        $project2->setId('second');
        $documentation->setProject($project);
        $this->expectException(HttpException::class);
        $this->invokeMethod($controller, 'support', [$project2, $documentation]);
    }
}
