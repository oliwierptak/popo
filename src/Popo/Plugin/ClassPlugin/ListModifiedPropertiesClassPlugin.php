<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class ListModifiedPropertiesClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = <<<EOF
return array_keys(\$this->updateMap);
EOF;

        $builder->getClass()
            ->addMethod('listModifiedProperties')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);
    }
}
