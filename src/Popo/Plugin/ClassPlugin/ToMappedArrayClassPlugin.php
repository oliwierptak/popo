<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class ToMappedArrayClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $this
            ->addToMappedArrayMethod($builder)
            ->addMapMethod($builder)
            ->addMapKeyNameMethod($builder);
    }

    protected function addToMappedArrayMethod(BuilderPluginInterface $builder): self
    {
        $body = <<<EOF
return \$this->map(\$this->toArray(), \$mappings);
EOF;

        $builder->getClass()
            ->addMethod('toMappedArray')
            ->setVariadic(true)
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body)
            ->addParameter('mappings');

        return $this;
    }

    protected function addMapMethod(BuilderPluginInterface $builder): self
    {
        $body = <<<EOF
\$result = [];
foreach (static::METADATA as \$name => \$propertyMetadata) {
    \$value = \$data[\$propertyMetadata['mappingPolicyValue']];

    if (static::METADATA[\$name]['type'] === 'popo') {
        \$popo = static::METADATA[\$name]['default'];
        \$value = \$this->\$name !== null ? \$this->\$name->toMappedArray(...\$mappings) : (new \$popo)->toMappedArray(...\$mappings);
    }

    \$key = \$this->mapKeyName(\$mappings, \$propertyMetadata['mappingPolicyValue']);
    \$result[\$key] = \$value;
}

return \$result;
EOF;

        $method = $builder->getClass()
            ->addMethod('map')
            ->setProtected()
            ->setReturnType('array')
            ->setBody($body);

        $method
            ->addParameter('data')
            ->setType('array');

        $method
            ->addParameter('mappings')
            ->setType('array');

        return $this;
    }

    protected function addMapKeyNameMethod(BuilderPluginInterface $builder): self
    {
        $mappingPolicyBody = '';
        $mappingPolicyCode = $builder->getSchemaMapper()->generateMappingPolicyPhpCode();
        foreach ($mappingPolicyCode as $mappingPolicy => $mappingCode) {
            $mappingPolicyBody .= sprintf(
                "
    \$mappingPolicy['%s'] =
        static function (string \$key): string {
            %s
        };
",
                $mappingPolicy,
                trim($mappingCode)
            );
        }

        $body = <<<EOF
static \$mappingPolicy = [];

if (empty(\$mappingPolicy)) {
    {$mappingPolicyBody}
}

foreach (\$mappings as \$mappingIndex => \$mappingType) {
    if (!array_key_exists(\$mappingType, \$mappingPolicy)) {
        continue;
    }

    \$key = \$mappingPolicy[\$mappingType](\$key);
}

return \$key;
EOF;

        $method = $builder->getClass()
            ->addMethod('mapKeyName')
            ->setProtected()
            ->setReturnType('string')
            ->setBody($body);

        $method
            ->addParameter('mappings')
            ->setType('array');

        $method
            ->addParameter('key')
            ->setType('string');

        return $this;
    }
}
