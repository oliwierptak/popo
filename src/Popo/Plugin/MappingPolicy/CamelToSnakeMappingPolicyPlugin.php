<?php

declare(strict_types = 1);

namespace Popo\Plugin\MappingPolicy;

use Popo\Plugin\MappingPolicyPluginInterface;
use function mb_strtolower;
use function preg_split;

class CamelToSnakeMappingPolicyPlugin implements MappingPolicyPluginInterface
{
    public const MAPPING_POLICY_NAME = 'camel-to-snake';
    
    public function run(string $key): string
    {
        $camelizedStringTokens = preg_split('/(?<=[^A-Z])(?=[A-Z])/', $key);
        if ($camelizedStringTokens !== false && count($camelizedStringTokens) > 0) {
            $key = mb_strtolower(implode('_', $camelizedStringTokens));
        }

        return $key;
    }
}
