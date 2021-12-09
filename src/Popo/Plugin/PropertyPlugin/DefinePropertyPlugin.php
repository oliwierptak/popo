<?php

declare(strict_types = 1);

namespace Popo\Plugin\PropertyPlugin;

use Nette\PhpGenerator\Literal;
use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\PropertyPluginInterface;
use Popo\Schema\Property;

class DefinePropertyPlugin implements PropertyPluginInterface
{
    public function run(BuilderPluginInterface $builder, Property $property): void
    {
        $value = $property->getDefault();
        if ($builder->getSchemaInspector()->isPopoProperty($property->getType()) ||
            $builder->getSchemaInspector()->isDateTimeProperty($property->getType())) {
            $value = null;
        }
        else if ($builder->getSchemaInspector()->isLiteral($property->getDefault())) {
            $value = new Literal($property->getDefault());
        }

        if ($value === null && $builder->getSchemaInspector()->isArray($property->getType())) {
            $value = [];
        }

        $builder->getClass()
            ->addProperty($property->getName(), $value)
            ->setComment($property->getComment())
            ->setProtected()
            ->setNullable($builder->getSchemaInspector()->isPropertyNullable($property))
            ->setType($builder->getSchemaInspector()->generatePopoType($builder->getSchema(), $property))
            ->setComment($property->getComment());
    }
}
