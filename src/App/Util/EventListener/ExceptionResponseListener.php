<?php

namespace App\Util\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExceptionResponseListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelResponse(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        $routeName = $request->get('_route');

        if ('app_' !== substr($routeName, 0, 4)) {
            return;
        }

        $exception =  $event->getException();
        $message = $exception->getMessage();
        $statusCode = 500;

        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode() ?: 500;
        }

        if ($exception instanceof BadRequestHttpException) {
            $message = str_replace('"', '\'', $message);
        }

        $content = ['error' => $message];
        $response = new JsonResponse($content, $statusCode);

        $event->setResponse($response);
    }
}
