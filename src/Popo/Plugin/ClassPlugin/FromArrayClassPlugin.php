<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class FromArrayClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = "\$metadata = [\n";
        foreach ($builder->getSchema()->getPropertyCollection() as $property) {
            $body .= sprintf(
                "\t'%s' => '%s',\n",
                $property->getName(),
                $builder->getSchemaMapper()->mapKeyName($property->getMappingPolicy(), $property->getName()),
            );
        }

        $body .= <<<EOF
];

foreach (\$metadata as \$name => \$mappedName) {
    \$meta = static::METADATA[\$name];
    \$value = \$data[\$mappedName] ?? \$this->\$name ?? null;
    \$popoValue = \$meta['default'];

    if (\$popoValue !== null && \$meta['type'] === 'popo') {
        \$popo = new \$popoValue;

        if (is_array(\$value)) {
            \$popo->fromArray(\$value);
        }

        \$value = \$popo;
    }

    if (\$meta['type'] === 'datetime') {
        if ((\$value instanceof DateTime) === false) {
            \$datetime = new DateTime(\$data[\$name] ?? \$meta['default']);
            \$timezone = \$meta['timezone'] ?? null;
            if (\$timezone !== null) {
                \$timezone = new DateTimeZone(\$timezone);
                \$datetime = new DateTime(\$data[\$name] ?? static::METADATA[\$name]['default'], \$timezone);
            }
            \$value = \$datetime;
        }
    }

    \$this->\$name = \$value;
    if (array_key_exists(\$mappedName, \$data)) {
        \$this->updateMap[\$name] = true;
    }
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
