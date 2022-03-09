<?php

declare(strict_types = 1);

namespace Popo\Plugin\PropertyPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\PropertyPluginInterface;
use Popo\Schema\Property\Property;

class DefinePropertyPlugin implements PropertyPluginInterface
{
    public function run(BuilderPluginInterface $builder, Property $property): void
    {
        $value = $builder->getSchemaGenerator()->generateDefaultTypeValue($property);

        $builder->getClass()
            ->addProperty($property->getName(), $value)
            ->setComment($property->getComment())
            ->setProtected()
            ->setNullable($builder->getSchemaInspector()->isPropertyNullable($property))
            ->setType($builder->getSchemaGenerator()->generatePopoType($builder->getSchema(), $property))
            ->setComment($property->getComment());
    }
}
