<?php

namespace App\EventSubscriber;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $req = $event->getRequest();
        if(substr($req->getPathInfo(),0,5)!=='/api/'){
            return;
        }
        $err = FlattenException::createFromThrowable($event->getThrowable());
        $resp = new JsonResponse([
            'code'=> $err->getStatusCode(),
            'message'=> $err->getStatusText()
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
