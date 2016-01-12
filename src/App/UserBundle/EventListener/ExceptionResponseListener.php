<?php

namespace App\UserBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        if ($exception instanceof NotFoundHttpException) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage();
        } elseif ($exception instanceof BadRequestHttpException) {
            $statusCode = 400;
            $message = $exception->getMessage();
            $message = str_replace('"', '\'', $message);
        } elseif ($exception instanceof ConflictHttpException) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage();
        } elseif ($exception instanceof AccessDeniedHttpException) {
            $statusCode = 403;
            $message = $exception->getMessage();
        } elseif ($exception instanceof \RuntimeException) {
            $statusCode = 400;
            $message = $exception->getMessage();
        }

        $content = ['error' => $message];
        $response = new JsonResponse($content, $statusCode);

        $event->setResponse($response);
    }
}
