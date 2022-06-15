<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class ExtendClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        if ($builder->getSchema()->getConfig()->getExtend() !== null) {
            $extend = str_replace('::class', '', $builder->getSchema()->getConfig()->getExtend());
            $builder->getClass()->setExtends($extend);
        }
    }
}
