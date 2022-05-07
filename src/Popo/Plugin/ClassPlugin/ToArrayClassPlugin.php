<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class ToArrayClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = "\$data = [\n";
        foreach ($builder->getSchema()->getPropertyCollection() as $property) {
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
        if (static::METADATA[\$name]['type'] === 'popo') {
            \$popo = static::METADATA[\$name]['default'];
            \$value = \$this->\$name !== null ? \$this->\$name->toArray() : (new \$popo)->toArray();
        }
        
        if (static::METADATA[\$name]['type'] === 'datetime') {
            if ((\$value instanceof \DateTime) === false) {
                \$datetime = new \DateTime(static::METADATA[\$name]['default']);
                \$timezone = static::METADATA[\$name]['timezone'] ?? null;
                if (\$timezone !== null) {
                    \$timezone = new \DateTimeZone(\$timezone);
                    \$datetime = new \DateTime(\$data[\$name] ?? static::METADATA[\$name]['default'], \$timezone);
                }
                \$value = \$datetime;
            }
            
            \$value = \$value->format(static::METADATA[\$name]['format']);            
        }
    }
);

return \$data;
EOF;

        $builder->getClass()
            ->addMethod('toArray')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);
    }
}
