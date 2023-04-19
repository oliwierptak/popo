<?php

declare(strict_types = 1);

namespace Popo\Plugin\MappingPolicy;

use Popo\Plugin\MappingPolicyPluginInterface;
use function mb_strtoupper;

class UpperMappingPolicyPlugin implements MappingPolicyPluginInterface
{
    public const MAPPING_POLICY_NAME = 'upper';

    public function run(string $key): string
    {
        return mb_strtoupper($key);
    }
}
