<?php

namespace App\EventSubscriber;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    const ERR_VALIDATION = '_err_validation';

    public function onKernelException(ExceptionEvent $event)
    {
        $req = $event->getRequest();
        if (substr($req->getPathInfo(), 0, 5) !== '/api/') {
            return;
        }
        $err = FlattenException::createFromThrowable($event->getThrowable());
        $msg = $err->getStatusText();
        if (true) { //TODO check for dev environment
            $msg = $err->getMessage();
        }
        $resp = new JsonResponse([
            'code' => $err->getStatusCode(),
            'message' => $msg,
            'errors' => $req->attributes->get(self::ERR_VALIDATION, [])
        ], $err->getStatusCode());
        $event->setResponse($resp);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
