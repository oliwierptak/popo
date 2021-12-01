<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Nette\PhpGenerator\ClassType;
use Popo\PluginInterface;
use Popo\Schema\Schema;

class FromArrayPlugin implements PluginInterface
{
    public function run(ClassType $class, Schema $schema): ClassType
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

        $class
            ->addMethod('fromArray')
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body)
            ->addParameter('data')
            ->setType('array');

        return $class;
    }
}
