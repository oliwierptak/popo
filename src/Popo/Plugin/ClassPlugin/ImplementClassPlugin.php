<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class ImplementClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        if ($builder->getSchema()->getConfig()->getImplement() !== null) {
            $implement = str_replace('::class', '', $builder->getSchema()->getConfig()->getImplement());
            $builder->getClass()->addImplement($implement);
        }
    }
}
