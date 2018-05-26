<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 01:38.
 */

namespace Happy\Command;

use Happy\Service\SwaggerDumpService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

/**
 * Class SwaggerDumpCommand.
 */
class SwaggerDumpCommand extends Command
{
    const COMMAND_NAME = 'happy:swagger:dump';

    /** @var SwaggerDumpService */
    private $dumpService;

    /** @var Client */
    private $client;

    /**
     * SwaggerDumpCommand constructor.
     *
     * @param SwaggerDumpService $dumpService
     * @param Client             $client
     */
    public function __construct(SwaggerDumpService $dumpService)
    {
        parent::__construct();
        $this->dumpService = $dumpService;
        $this->client      = new Client();
    }

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)->setDescription('dump swagger response');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $json = $this->dumpService->getSwaggerDoc();

//        $r = $this->client->request('POST', 'http://localhost:80/api/projects/01442b9f-bd75-409e-8535-f34346b4cebf/documentations/raw', [
//            'body' => $json
//        ]);

        $output->write('<info>'.$json.'</info>');
        $output->write(PHP_EOL);

        return 0;
    }
}
