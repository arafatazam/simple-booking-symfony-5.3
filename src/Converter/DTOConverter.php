<?php

namespace App\Converter;

use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class DTOConverter implements ParamConverterInterface
{
    private $serializer;

    const DTO_PARAM = '_dto_parameter';
    const DTO_GROUPS = '_dto_groups';

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(ParamConverter $configuration)
    {
        $class = $configuration->getClass();
        if (substr($class, 0, 7) === 'App\\DTO') {
            return true;
        }
        return false;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $data = $request->getContent();
        $class = $configuration->getClass();
        $dto = $this->serializer->deserialize($data, $class, 'json');
        $request->attributes->set($configuration->getName(), $dto);
        $request->attributes->set(self::DTO_PARAM, $configuration->getName());
        $options = $configuration->getOptions();
        if (isset($options['groups'])) {
            $request->attributes->set(self::DTO_GROUPS, $options['groups']);
        }
        return true;
    }
}
