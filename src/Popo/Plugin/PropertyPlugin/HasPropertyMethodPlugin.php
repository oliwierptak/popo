<?php

declare(strict_types = 1);

namespace Popo\Plugin\PropertyPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\PropertyPluginInterface;
use Popo\Schema\Property\Property;

class HasPropertyMethodPlugin implements PropertyPluginInterface
{
    public function run(BuilderPluginInterface $builder, Property $property): void
    {
        $name = $property->getName();

        $body = <<<EOF
return \$this->${name} !== null;
EOF;

        if ($builder->getSchemaInspector()->isArray($property->getType())) {
            $name = $property->getItemName() ?? $property->getName();
            $name = $name . 'Collection';

            $body = <<<EOF
return !empty(\$this->${name});
EOF;
        }

        $builder->getClass()
            ->addMethod('has' . ucfirst($name))
            ->setPublic()
            ->setReturnType('bool')
            ->setBody($body);
    }
}
