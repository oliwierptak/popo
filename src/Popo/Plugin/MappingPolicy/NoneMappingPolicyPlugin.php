<?php

declare(strict_types = 1);

namespace Popo\Plugin\MappingPolicy;

use Popo\Plugin\MappingPolicyPluginInterface;

class NoneMappingPolicyPlugin implements MappingPolicyPluginInterface
{
    public const MAPPING_POLICY_NAME = 'none';

    public function run(string $key): string
    {
        return $key;
    }
}
