<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class ClassAttributePlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        if ($builder->getSchema()->getConfig()->getAttribute() !== null) {
            $builder->getClass()->setAttributes(
                $builder->getSchemaGenerator()->parseAttributes(
                    $builder->getSchema()->getConfig()->getAttribute(),
                    $builder->getSchema()->getConfig()->getAttributes(),
                ),
            );
        }
    }
}
