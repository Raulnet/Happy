<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 17/05/18
 * Time: 00:18.
 */

namespace Happy\Service\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ExceptionListener.
 */
class ExceptionListener
{
    const ENV_DEV = 'dev';
    const PROD_DEFAULT_MESSAGE_SERVER_ERROR = 'an error has happened';
    /** @var string */
    private $env;

    /**
     * ExceptionListener constructor.
     *
     * @param string $env
     */
    public function __construct(string $env = 'prod')
    {
        $this->env = $env;
    }

    /** @param GetResponseForExceptionEvent $event */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $response = $this->getResponse($event->getException());
        $event->setResponse($response);
    }

    /**
     * @param \Exception $exception
     *
     * @return array
     */
    private function getMessage(\Exception $exception): array
    {
        $message = [
            'type' => $this->getNameException($exception),
            'message' => $exception->getMessage(),
        ];

        if (($exception instanceof HttpException) || ($exception instanceof NotFoundHttpException)) {
            $message['code'] = $exception->getStatusCode() ?: JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        } else {
            $message['code'] = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
            if (self::ENV_DEV !== $this->env) {
                $message['message'] = self::PROD_DEFAULT_MESSAGE_SERVER_ERROR;
            }
        }
        if (self::ENV_DEV === $this->env) {
            $message += [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ];
        }

        return $message;
    }

    /**
     * @param \Exception $exception
     *
     * @return JsonResponse
     */
    private function getResponse(\Exception $exception): JsonResponse
    {
        $response = new JsonResponse();
        $message = $this->getMessage($exception);
        if ($exception instanceof HttpExceptionInterface) {
            $response->headers->replace($exception->getHeaders());
        }
        $response->setStatusCode($message['code']);

        return $response->setData($message);
    }

    /**
     * @param \Exception $exception
     *
     * @return string
     */
    private function getNameException(\Exception $exception): string
    {
        $className = (string) get_class($exception);
        $tmp = explode('\\', $className);

        return end($tmp);
    }
}