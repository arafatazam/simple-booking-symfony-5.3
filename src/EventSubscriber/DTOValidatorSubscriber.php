<?php

namespace App\EventSubscriber;

use App\Converter\DTOConverter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOValidatorSubscriber implements EventSubscriberInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event)
    {
        $request = $event->getRequest();
        $dtoParam = $request->attributes->get(DTOConverter::DTO_PARAM);
        if (null == $dtoParam) {
            return;
        }
        $dto = $request->attributes->get($dtoParam);
        $groups = $request->attributes->get(DTOConverter::DTO_GROUPS);
        $violations = $this->validator->validate($dto, null, $groups);
        if (count($violations) == 0) {
            return;
        }
        $err = [];
        foreach ($violations as $v) {
            $err[$v->getPropertyPath()][] = $v->getMessage();
        }
        $request->attributes->set(ExceptionSubscriber::ERR_VALIDATION, $err);
        throw new BadRequestException("Invalid request");
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller_arguments' => 'onKernelControllerArguments',
        ];
    }
}
