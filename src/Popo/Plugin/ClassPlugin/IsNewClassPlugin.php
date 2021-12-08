<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class IsNewClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = <<<EOF
return empty(\$this->updateMap) === true;
EOF;

        $builder->getClass()
            ->addMethod('isNew')
            ->setPublic()
            ->setReturnType('bool')
            ->setBody($body);
    }
}
