<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 00:30.
 */

namespace Happy\Tests\Service\EventListener;

use Happy\Tests\AbstractTestCase;
use Happy\Service\EventListener\ExceptionListener;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ExceptionListenerTest.
 */
class ExceptionListenerTest extends AbstractTestCase
{
    const EXCEPTION_MESSAGE = 'test exception';
    const HTTPEXCEPTION_MESSAGE = 'test httpexception';
    /** @var MockObject */
    private $event;
    /** @var \Exception */
    private $exception;
    /** @var HttpException */
    private $httpException;

    /**
     * Setup Test.
     *
     * Init Event
     * Init Exception \ HttpException
     */
    public function setup()
    {
        $this->event = $this->getMockBuilder(GetResponseForExceptionEvent::class)->disableOriginalConstructor()->getMock();
        $this->exception = new \Exception(self::EXCEPTION_MESSAGE, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        $this->httpException = new HttpException(JsonResponse::HTTP_CONFLICT, self::HTTPEXCEPTION_MESSAGE);
    }

    /**
     * testOnkernelExceptionProd.
     *
     * test Exception on env Prod
     *
     * @throws \ReflectionException
     */
    public function testOnkernelExceptionProd()
    {
        $exception = $this->exception;
        $this->event->expects($this->once())->method('getException')->willReturn($exception);
        $exceptionListener = new ExceptionListener('prod');
        //test full process
        //the process return void
        $this->assertNull($exceptionListener->onKernelException($this->event));
        //test method getMessage
        $message = $this->invokeMethod($exceptionListener, 'getMessage', [$exception]);
        $this->assertTrue(is_array($message));
        $this->assertTrue('Exception' === $message['type']);
        $this->assertTrue(JsonResponse::HTTP_INTERNAL_SERVER_ERROR === $message['code']);
        $this->assertTrue(ExceptionListener::PROD_DEFAULT_MESSAGE_SERVER_ERROR === $message['message']);
    }

    /**
     * testOnkernelExceptionDev.
     *
     * test Exception on Environnement Dev
     *
     * @throws \ReflectionException
     */
    public function testOnkernelExceptionDev()
    {
        $exception = $this->exception;
        $this->event->expects($this->once())->method('getException')->willReturn($exception);
        $exceptionListener = new ExceptionListener(ExceptionListener::ENV_DEV);
        //test full process
        //the process return void
        $this->assertNull($exceptionListener->onKernelException($this->event));
        //test method getMessage
        $message = $this->invokeMethod($exceptionListener, 'getMessage', [$exception]);
        $this->assertTrue(is_array($message));
        $this->assertTrue('Exception' === $message['type']);
        $this->assertTrue(JsonResponse::HTTP_INTERNAL_SERVER_ERROR === $message['code']);
        $this->assertTrue(self::EXCEPTION_MESSAGE === $message['message']);
        $this->assertTrue(array_key_exists('trace', $message));
    }

    /**
     * testOnKernelHttpExceptionProd.
     *
     * test HttpException on Environnement Prod
     *
     * @throws \ReflectionException
     */
    public function testOnKernelHttpExceptionProd()
    {
        $httpEXception = new HttpException(JsonResponse::HTTP_CONFLICT, self::HTTPEXCEPTION_MESSAGE);
        $this->event->expects($this->once())->method('getException')->willReturn($httpEXception);
        $exceptionListener = new ExceptionListener('prod');
        //test full process
        //the process return void
        $this->assertNull($exceptionListener->onKernelException($this->event));
        //test method getMessage
        $message = $this->invokeMethod($exceptionListener, 'getMessage', [$httpEXception]);
        $this->assertTrue(is_array($message));
        $this->assertTrue('HttpException' === $message['type']);
        $this->assertTrue(JsonResponse::HTTP_CONFLICT === $message['code']);
        $this->assertTrue(self::HTTPEXCEPTION_MESSAGE === $message['message']);
    }

    /**
     * testOnKernelHttpExceptionProd.
     *
     * test NotHttpException on Environnement Prod
     *
     * @throws \ReflectionException
     */
    public function testOnKernelNotFoundHttpExceptionProd()
    {
        $httpEXception = new NotFoundHttpException(self::HTTPEXCEPTION_MESSAGE);
        $this->event->expects($this->once())->method('getException')->willReturn($httpEXception);
        $exceptionListener = new ExceptionListener('prod');
        //test full process
        //the process return void
        $this->assertNull($exceptionListener->onKernelException($this->event));
        //test method getMessage
        $message = $this->invokeMethod($exceptionListener, 'getMessage', [$httpEXception]);
        $this->assertTrue(is_array($message));
        $this->assertTrue('NotFoundHttpException' === $message['type']);
        $this->assertTrue(JsonResponse::HTTP_NOT_FOUND === $message['code']);
        $this->assertTrue(self::HTTPEXCEPTION_MESSAGE === $message['message']);
    }

    /**
     * testOnKernelHttpExceptionProd.
     *
     * test HttpException on Environnement Dev
     *
     * @throws \ReflectionException
     */
    public function testOnKernelHttpExceptionDev()
    {
        $httpEXception = new HttpException(Response::HTTP_CONFLICT, self::HTTPEXCEPTION_MESSAGE);
        $this->event->expects($this->once())->method('getException')->willReturn($httpEXception);
        $exceptionListener = new ExceptionListener('dev');
        //test full process
        //the process return void
        $this->assertNull($exceptionListener->onKernelException($this->event));
        //test method getMessage
        $message = $this->invokeMethod($exceptionListener, 'getMessage', [$httpEXception]);
        $this->assertTrue(is_array($message));
        $this->assertTrue('HttpException' === $message['type']);
        $this->assertTrue(JsonResponse::HTTP_CONFLICT === $message['code']);
        $this->assertTrue(self::HTTPEXCEPTION_MESSAGE === $message['message']);
        $this->assertTrue(array_key_exists('trace', $message));
    }

    /**
     * testGetResponse.
     *
     * test private method getResponse env prod/dev
     *
     * @throws \ReflectionException
     */
    public function testGetResponse()
    {
        $exceptionListener = new ExceptionListener();
        //test exception
        $exception = new \Exception(self::EXCEPTION_MESSAGE);
        $response = $this->invokeMethod($exceptionListener, 'getResponse', [$exception]);
        $this->assertTrue($response instanceof JsonResponse);
        //test httpException
        $httpEXception = new HttpException(JsonResponse::HTTP_CONFLICT, self::HTTPEXCEPTION_MESSAGE);
        $response = $this->invokeMethod($exceptionListener, 'getResponse', [$httpEXception]);
        $this->assertTrue($response instanceof JsonResponse);
        //test NotHttpException
        $NotFoundHttpException = new NotFoundHttpException(self::HTTPEXCEPTION_MESSAGE);
        $response = $this->invokeMethod($exceptionListener, 'getResponse', [$NotFoundHttpException]);
        $this->assertTrue($response instanceof JsonResponse);
    }

    /**
     * testGetNameException.
     *
     * test private method getNameException env prod/dev
     *
     * @throws \ReflectionException
     */
    public function testGetNameException()
    {
        $exceptionListener = new ExceptionListener();
        //test exception
        $exception = new \Exception(self::EXCEPTION_MESSAGE);
        $name = $this->invokeMethod($exceptionListener, 'getNameException', [$exception]);
        $this->assertTrue('Exception' === $name);
        //test httpException
        $httpEXception = new HttpException(Response::HTTP_CONFLICT, self::HTTPEXCEPTION_MESSAGE);
        $name = $this->invokeMethod($exceptionListener, 'getNameException', [$httpEXception]);
        $this->assertTrue('HttpException' === $name);
        //test NotFoundHttpException
        $httpEXception = new NotFoundHttpException(self::HTTPEXCEPTION_MESSAGE);
        $name = $this->invokeMethod($exceptionListener, 'getNameException', [$httpEXception]);
        $this->assertTrue('NotFoundHttpException' === $name);
    }
}