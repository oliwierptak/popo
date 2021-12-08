<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class PopoMethodClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = <<<EOF
if (static::METADATA[\$propertyName]['type'] === 'popo' && \$this->\$propertyName === null) {
    \$popo = static::METADATA[\$propertyName]['default'];
    \$this->\$propertyName = new \$popo;
}
EOF;

        $builder->getClass()
            ->addMethod('setupPopoProperty')
            ->setProtected()
            ->setReturnType('void')
            ->setBody($body)
            ->addParameter('propertyName');
    }
}
