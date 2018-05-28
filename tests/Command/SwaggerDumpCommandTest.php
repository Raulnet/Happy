<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 13:18.
 */

namespace Happy\Tests\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Happy\Command\SwaggerDumpCommand;
use Happy\Service\SwaggerDumpService;
use PHPUnit\Framework\MockObject\MockBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpFoundation\JsonResponse;

class SwaggerDumpCommandTest extends WebTestCase
{
    const CONTAINT = '{"swagger":"2.0"}';

    /** @var ContainerInterface */
    private $client;

    /** @var MockBuilder */
    private $mockSwaggerDumpService;

    private $mockGuzzleClient;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->mockSwaggerDumpService = $this->getMockBuilder(SwaggerDumpService::class)
                                             ->disableOriginalConstructor()
                                             ->getMock();
        $this->mockSwaggerDumpService->expects($this->once())->method('getSwaggerDoc')->willReturn(self::CONTAINT);

        $this->mockGuzzleClient = $this->getMockBuilder(Client::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $response = new Response(JsonResponse::HTTP_CREATED, [], (string) JsonResponse::HTTP_CREATED);
        $this->mockGuzzleClient->expects($this->once())->method('request')->willReturn($response);
    }

    public function testExcute()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new SwaggerDumpCommand($this->mockSwaggerDumpService, $this->mockGuzzleClient));

        $command = $application->find('happy:swagger:dump');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains((string) JsonResponse::HTTP_CREATED, $output);
    }
}
