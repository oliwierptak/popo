<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class ClassCommentPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        if ($builder->getSchema()->getConfig()->getComment() !== null) {
            $builder->getClass()->setComment($builder->getSchema()->getConfig()->getComment());
        }
    }
}
