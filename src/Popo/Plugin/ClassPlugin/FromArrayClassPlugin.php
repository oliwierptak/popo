<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class FromArrayClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = <<<EOF
foreach (static::METADATA as \$name => \$meta) {
    \$value = \$data[\$name] ?? \$this->\$name ?? null;
    \$popoValue = \$meta['default'];

    if (\$popoValue !== null && \$meta['type'] === 'popo') {
        \$popo = new \$popoValue;

        if (is_array(\$value)) {
            \$popo->fromArray(\$value);
        }

        \$value = \$popo;
    }

    \$this->\$name = \$value;
    \$this->updateMap[\$name] = true;
}

return \$this;
EOF;

        $builder->getClass()
            ->addMethod('fromArray')
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body)
            ->addParameter('data')
            ->setType('array');
    }
}