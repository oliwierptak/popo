<?php

declare(strict_types = 1);

namespace Popo\Plugin\MappingPolicy;

use Popo\Plugin\MappingPolicyPluginInterface;
use function array_shift;
use function explode;
use function mb_strtolower;
use function ucfirst;

class SnakeToCamelMappingPolicyPlugin implements MappingPolicyPluginInterface
{
    public const MAPPING_POLICY_NAME = 'snake-to-camel';

    public function run(string $key): string
    {
        $stringTokens = explode('_', mb_strtolower($key));
        $camelizedString = array_shift($stringTokens);
        foreach ($stringTokens as $token) {
            $camelizedString .= ucfirst($token);
        }

        return $camelizedString;
    }
}
