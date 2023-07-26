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
                $property->getMappingPolicyValue() ?? $builder->getSchemaMapper()
                    ->mapKeyName($property->getMappingPolicy(), $property->getName()),
            );
        }

        $parentCallString = '';
        $hasParent = $builder->getSchema()->getConfig()->getExtend() !== null;
        if ($hasParent) {
            $parentCallString = <<<EOF
if (method_exists(get_parent_class(\$this), 'toArray')) {
    parent::fromArray(\$data);
}
EOF;
        }
        $body .= <<<EOF
];

$parentCallString

foreach (\$metadata as \$name => \$mappedName) {
    \$meta = self::METADATA[\$name];
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
            \$datetime = new DateTime(\$data[\$name] ?? \$meta['default'] ?: 'now');
            \$timezone = \$meta['timezone'] ?? null;
            if (\$timezone !== null) {
                \$timezone = new DateTimeZone(\$timezone);
                \$datetime = new DateTime(\$data[\$name] ?? self::METADATA[\$name]['default'] ?: 'now', \$timezone);
            }
            \$value = \$datetime;
        }
    }
    
    if (\$meta['type'] === 'array' && isset(\$meta['itemIsPopo']) && \$meta['itemIsPopo']) {
        \$className = \$meta['itemType'];

        \$valueCollection = [];
        foreach (\$value as \$popoKey => \$popoValue) {
            \$popo = new \$className;
            \$popo->fromArray(\$popoValue);

            \$valueCollection[] = \$popo;
        }

        \$value = \$valueCollection;
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
