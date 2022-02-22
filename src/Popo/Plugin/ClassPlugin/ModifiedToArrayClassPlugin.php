<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class ModifiedToArrayClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = <<<EOF
\$data = \$this->toArray();
\$modifiedProperties = \$this->listModifiedProperties();

return \array_filter(\$data, function (\$key) use (\$modifiedProperties) {
    return \in_array(\$key, \$modifiedProperties);
}, \ARRAY_FILTER_USE_KEY);
EOF;

        $builder->getClass()
            ->addMethod('modifiedToArray')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);
    }
}
