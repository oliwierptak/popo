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
        $type = $builder->getSchemaGenerator()->generatePopoType($builder->getSchema(), $property);
        $isNullable = $builder->getSchemaInspector()->isPropertyNullable($property);
        $type = $isNullable ? '?'.$type : $type;

        $builder->getClass()
            ->addProperty($property->getName(), $value)
            ->setProtected()
            ->setNullable($isNullable)
            ->setType($type)
            ->setComment($property->getComment());
    }
}
