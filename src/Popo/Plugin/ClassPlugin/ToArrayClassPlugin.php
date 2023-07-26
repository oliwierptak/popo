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

        $dataParentCall = '';
        $hasParent = $builder->getSchema()->getConfig()->getExtend() !== null;
        if ($hasParent) {
            $dataParentCall = <<<EOF
if (method_exists(get_parent_class(\$this), 'toArray')) {
    \$data = array_replace_recursive(parent::toArray(), \$data);
}
EOF;

        }

        $body .= <<<EOF
];

\$data = [];

foreach (\$metadata as \$name => \$mappedName) {
    \$value = \$this->\$name;

    if (self::METADATA[\$name]['type'] === 'popo') {
        \$popo = self::METADATA[\$name]['default'];
        \$value = \$this->\$name !== null ? \$this->\$name->toArray() : (new \$popo)->toArray();
    }

    if (self::METADATA[\$name]['type'] === 'datetime') {
        if ((\$value instanceof DateTime) === false) {
            \$datetime = new DateTime(self::METADATA[\$name]['default'] ?: 'now');
            \$timezone = self::METADATA[\$name]['timezone'] ?? null;
            if (\$timezone !== null) {
                \$timezone = new DateTimeZone(\$timezone);
                \$datetime = new DateTime(\$this->\$name ?? self::METADATA[\$name]['default'] ?: 'now', \$timezone);
            }
            \$value = \$datetime;
        }

        \$value = \$value->format(self::METADATA[\$name]['format']);
    }
    
    if (self::METADATA[\$name]['type'] === 'array' && isset(self::METADATA[\$name]['itemIsPopo']) && self::METADATA[\$name]['itemIsPopo']) {
        \$valueCollection = [];
        foreach (\$value as \$popo) {
            \$valueCollection[] = \$popo->toArray();
        }

        \$value = \$valueCollection;
    }

    \$data[\$mappedName] = \$value;
}

$dataParentCall

return \$data;
EOF;

        $builder->getClass()
            ->addMethod('toArray')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);
    }
}
