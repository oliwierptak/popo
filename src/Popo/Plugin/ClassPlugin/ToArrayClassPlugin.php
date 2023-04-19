<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class ToArrayClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = "\$metadata = [\n";
        foreach ($builder->getSchema()->getPropertyCollection() as $property) {
            $body .= sprintf(
                "\t'%s' => '%s',\n",
                $property->getName(),
                $property->getMappingPolicyValue() ?? $builder->getSchemaMapper()
                    ->mapKeyName($property->getMappingPolicy(), $property->getName()),
            );
        }
        $body .= <<<EOF
];

\$data = [];
foreach (\$metadata as \$name => \$mappedName) {
    \$value = \$this->\$name;

    if (static::METADATA[\$name]['type'] === 'popo') {
        \$popo = static::METADATA[\$name]['default'];
        \$value = \$this->\$name !== null ? \$this->\$name->toArray() : (new \$popo)->toArray();
    }

    if (static::METADATA[\$name]['type'] === 'datetime') {
        if ((\$value instanceof DateTime) === false) {
            \$datetime = new DateTime(static::METADATA[\$name]['default']);
            \$timezone = static::METADATA[\$name]['timezone'] ?? null;
            if (\$timezone !== null) {
                \$timezone = new DateTimeZone(\$timezone);
                \$datetime = new DateTime(\$this->\$name ?? static::METADATA[\$name]['default'], \$timezone);
            }
            \$value = \$datetime;
        }

        \$value = \$value->format(static::METADATA[\$name]['format']);
    }

    \$data[\$mappedName] = \$value;
}

return \$data;
EOF;

        $builder->getClass()
            ->addMethod('toArray')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);
    }
}
