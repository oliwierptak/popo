<?php

declare(strict_types = 1);

namespace Popo\Plugin\PropertyPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\PropertyPluginInterface;
use Popo\Schema\Property\Property;

class RequirePropertyMethodPlugin implements PropertyPluginInterface
{
    public function run(BuilderPluginInterface $builder, Property $property): void
    {
        $name = $property->getName();

        $body = <<<EOF
\$this->setupPopoProperty('${name}');
\$this->setupDateTimeProperty('${name}');

if (%s) {
    throw new UnexpectedValueException('Required value of "${name}" has not been set');
}
return \$this->${name};
EOF;

        $condition = $builder->getSchemaInspector()->isArray($property->getType())
            ? "empty(\$this->${name})"
            : "\$this->${name} === null";

        $body = sprintf($body, $condition);

        $builder->getClass()
            ->addMethod('require' . ucfirst($property->getName()))
            ->setComment($property->getComment())
            ->setPublic()
            ->setReturnType($builder->getSchemaGenerator()->generatePopoType($builder->getSchema(), $property))
            ->setBody($body);
    }
}
