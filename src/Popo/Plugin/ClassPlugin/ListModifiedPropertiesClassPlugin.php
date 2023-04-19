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
\$sorted = array_keys(\$this->updateMap);
sort(\$sorted, SORT_STRING);
return \$sorted;
EOF;

        $builder->getClass()
            ->addMethod('listModifiedProperties')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);
    }
}
