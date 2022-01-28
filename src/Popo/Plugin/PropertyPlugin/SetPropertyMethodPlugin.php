<?php

declare(strict_types = 1);

namespace Popo\Plugin\PropertyPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\PropertyPluginInterface;
use Popo\Schema\Property\Property;

class SetPropertyMethodPlugin implements PropertyPluginInterface
{
    public function run(BuilderPluginInterface $builder, Property $property): void
    {
        $method = $builder->getClass()
            ->addMethod('set' . ucfirst($property->getName()))
            ->setComment($property->getComment())
            ->setPublic()
            ->setReturnType('self')
            ->setBody(
                sprintf(
                    '$this->%s = $%s; $this->updateMap[\'%s\'] = true; return $this;',
                    $property->getName(),
                    $property->getName(),
                    $property->getName(),
                )

            );

        $nullable = $builder->getSchemaInspector()->isPropertyNullable($property);

        $method
            ->addParameter($property->getName())
            ->setType($builder->getSchemaGenerator()->generatePopoType($builder->getSchema(), $property))
            ->setNullable($nullable);
    }
}
