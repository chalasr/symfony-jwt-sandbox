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

        $statusCode = 500;
        $message = $exception->getMessage();

        if ($exception instanceof NotFoundHttpException) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage();
        } elseif ($exception instanceof BadRequestHttpException) {
            $statusCode = 400;
            $message = $exception->getMessage();
            $message = str_replace('"', '\'', $message);
            // if (false !== strpos($message, 'Request parameter')) { // 19 = property 1st char
            //     print_r($message);die;
            //     $validationMessage = [];
            //     $valueStart = strpos($message, 'value');
            //     $validationMessage['property'] = substr($message, 18, $valueStart-19);
            //     $validationMessage['message']  = substr($message, strpos($message, '(')+1, 31);
            //     $message = $validationMessage;
            // }
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
