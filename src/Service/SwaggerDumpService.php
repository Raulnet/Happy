<?php

namespace Happy\Service;

use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\ApiDocGenerator;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 11:19.
 */
class SwaggerDumpService
{
    Const CONTROLLER_SWAGGER = 'nelmio_api_doc.controller.swagger';

    /**
     * @var ServiceLocator
     */
    private $generatorLocator;

    /**
     * SwaggerDumpService constructor.
     *
     * @param ApiDocGenerator $generatorLocator
     */
    public function __construct(ApiDocGenerator $generatorLocator)
    {
        $this->generatorLocator = new ServiceLocator([
            'default' => function () use ($generatorLocator): ApiDocGenerator
            {
                return $generatorLocator;
            },
        ]);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getSwaggerDoc()
    {
        $spec = $this->generatorLocator->get('default')->generate()->toArray();

        return json_encode($spec);
    }
}
