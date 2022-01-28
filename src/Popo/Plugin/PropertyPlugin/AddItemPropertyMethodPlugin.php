<?php

declare(strict_types = 1);

namespace Popo\Plugin\PropertyPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\PropertyPluginInterface;
use Popo\Schema\Property\Property;

class AddItemPropertyMethodPlugin implements PropertyPluginInterface
{
    public function run(BuilderPluginInterface $builder, Property $property): void
    {
        if ($property->getItemType() === null ||
            $builder->getSchemaInspector()->isArray($property->getType()) === false) {
            return;
        }

        $name = $property->getName();

        $body = <<<EOF
\$this->${name}[] = \$item;

\$this->updateMap['${name}'] = true;

return \$this;
EOF;

        $name = $property->getItemName() ?? $property->getName() . 'Item';

        $builder->getClass()
            ->addMethod('add' . ucfirst($name))
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body)
            ->addParameter('item')
            ->setType(
                $builder->getSchemaGenerator()->generatePopoItemType(
                    $builder->getSchema(),
                    $property
                )
            );
    }
}
