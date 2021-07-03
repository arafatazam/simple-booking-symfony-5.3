<?php

namespace App\Converter;

use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class DTOConverter implements ParamConverterInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(ParamConverter $configuration)
    {
        $class = $configuration->getClass();
        return substr($class, 0, 7) === 'App\\DTO';
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $data = $request->getContent();
        $class = $configuration->getClass();
        $dto = $this->serializer->deserialize($data, $class, 'json');
        $request->attributes->set($configuration->getName(), $dto);
        return true;
    }
}
