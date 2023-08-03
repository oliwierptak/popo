<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Nette\PhpGenerator\TraitUse;
use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class  TraitPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $traits = [];
        foreach ($builder->getSchema()->getConfig()->getTrait() as $traitName) {
            $traits[] = (new TraitUse($traitName, $builder->getClass()));
        }

        $builder->getClass()->setTraits($traits);
    }
}
