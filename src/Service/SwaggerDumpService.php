<?php

namespace Happy\Service;

use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 12/05/18
 * Time: 11:19.
 */
class SwaggerDumpService
{
    Const CONTROLLER_SWAGGER = 'nelmio_api_doc.controller.swagger';

    /** @var HttpKernel */
    private $httpKernel;

    /**
     * SwaggerDumpService constructor.
     *
     * @param HttpKernel $httpKernel
     */
    public function __construct(HttpKernel $httpKernel)
    {
        $this->httpKernel = $httpKernel;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getSwaggerDoc() {
        $path['_controller'] = self::CONTROLLER_SWAGGER;
        $request = new Request([], [], $path);
        /** @var JsonResponse $response */
        $response = $this->httpKernel->handle($request, HttpKernelInterface::SUB_REQUEST);

        return $response->getContent();
    }
}
