<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin\MappingPolicyMethod;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;
use Popo\Plugin\MappingPolicy\UpperMappingPolicyPlugin;

class ToArrayUppercasePlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = <<<EOF
return \$this->toMappedArray('%s');
EOF;

        $body = sprintf($body, UpperMappingPolicyPlugin::MAPPING_POLICY_NAME);

        $builder->getClass()
            ->addMethod('toArrayUpper')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);
    }
}
