<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class FromMappedArrayClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = <<<EOF
\$result = [];
foreach (static::METADATA as \$name => \$propertyMetadata) {
    \$mappingPolicyValue = \$propertyMetadata['mappingPolicyValue'];
    \$inputKey = \$this->mapKeyName(\$mappings, \$mappingPolicyValue);
    \$value = \$data[\$inputKey] ?? null;

    if (static::METADATA[\$name]['type'] === 'popo') {
        \$popo = static::METADATA[\$name]['default'];
        \$value = \$this->\$name !== null
            ? \$this->\$name->fromMappedArray(\$value ?? [], ...\$mappings)
            : (new \$popo)->fromMappedArray(\$value ?? [], ...\$mappings);
        \$value = \$value->toArray();    
    }

    \$result[\$mappingPolicyValue] = \$value;
}

\$this->fromArray(\$result);

return \$this;
EOF;

        $method = $builder->getClass()->addMethod('fromMappedArray');
        $method
            ->setVariadic(true)
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body);
        $method
            ->addParameter('data')
            ->setType('array');
        $method
            ->addParameter('mappings');
    }
}
