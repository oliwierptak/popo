<?php

declare(strict_types = 1);

namespace Popo\Plugin\MappingPolicy;

use Popo\Plugin\MappingPolicyPluginInterface;
use function mb_strtolower;

class LowerMappingPolicyPlugin implements MappingPolicyPluginInterface
{
    public const MAPPING_POLICY_NAME = 'lower';

    public function run(string $key): string
    {
        return mb_strtolower($key);
    }
}
