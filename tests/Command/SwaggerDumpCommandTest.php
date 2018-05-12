<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 13:18
 */

namespace Happy\Tests\Command;


use Happy\Command\SwaggerDumpCommand;
use Happy\Service\SwaggerDumpService;
use Happy\Tests\AbstractCommandTestCase;
use PHPUnit\Framework\MockObject\MockBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SwaggerDumpCommandTest extends WebTestCase
{
    const CONTAINT = '{"swagger":"2.0"}';

    /** @var ContainerInterface */
    private $client;

    /** @var MockBuilder */
    private $mockSwaggerDumpService;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->mockSwaggerDumpService = $this->getMockBuilder(SwaggerDumpService::class)
                                             ->disableOriginalConstructor()
                                             ->getMock();
        $this->mockSwaggerDumpService->expects($this->once())->method('getSwaggerDoc')->willReturn(self::CONTAINT);

    }

    public function testExcute()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new SwaggerDumpCommand($this->mockSwaggerDumpService));

        $command = $application->find('happy:swagger:dump');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName()
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains(self::CONTAINT,$output);
    }
}