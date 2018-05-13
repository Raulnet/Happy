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

/**
 * Class SwaggerDumpCommand.
 */
class SwaggerDumpCommand extends Command
{
    const COMMAND_NAME = 'happy:swagger:dump';

    /** @var SwaggerDumpService */
    private $dumpService;

    /**
     * SwaggerDumpCommand constructor.
     *
     * @param SwaggerDumpService $dumpService
     */
    public function __construct(SwaggerDumpService $dumpService)
    {
        parent::__construct(self::COMMAND_NAME);
        $this->setDescription('dump swagger response');
        $this->dumpService = $dumpService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $json = $this->dumpService->getSwaggerDoc();

        $output->write('<info>'.$json.'</info>');
        $output->write(PHP_EOL);

        return 0;
    }
}