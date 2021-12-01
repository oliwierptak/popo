<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Nette\PhpGenerator\ClassType;
use Popo\PluginInterface;
use Popo\Schema\Schema;

class ToArrayPlugin implements PluginInterface
{
    public function run(ClassType $class, Schema $schema): ClassType
    {
        $body = "\$data = [\n";
        foreach ($schema->getPropertyCollection() as $property) {
            $body .= sprintf(
                "\t'%s' => \$this->%s,\n",
                $property->getName(),
                $property->getName()
            );
        }

        $body .= <<<EOF
];

array_walk(
    \$data,
    function (&\$value, \$name) use (\$data) {
        \$popo = static::METADATA[\$name]['default'];
        if (static::METADATA[\$name]['type'] === 'popo') {
            \$value = \$this->\$name !== null ? \$this->\$name->toArray() : (new \$popo)->toArray();
        }
    }
);

return \$data;
EOF;

        $class
            ->addMethod('toArray')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);

        return $class;
    }
}
