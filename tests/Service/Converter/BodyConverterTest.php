<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 25/05/18
 * Time: 00:01.
 */

namespace Happy\Tests\Service\Converter;

use Happy\Service\Converter\BodyConverter;
use Happy\Service\Converter\EntityConverterService;
use Happy\Tests\AbstractTestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BodyConverterTest.
 */
class BodyConverterTest extends AbstractTestCase
{
    public function testApplyHttpException()
    {
        $entityConverterService = $this->getMockBuilder(EntityConverterService::class)->disableOriginalConstructor()->getMock();
        $paramConverter = $this->getMockBuilder(ParamConverter::class)->disableOriginalConstructor()->getMock();
        $bodyConverter = new BodyConverter($entityConverterService);
        $request = new Request();
        $request->setMethod('GET');
        $this->expectException(HttpException::class);
        $bodyConverter->apply($request, $paramConverter);
    }
}
