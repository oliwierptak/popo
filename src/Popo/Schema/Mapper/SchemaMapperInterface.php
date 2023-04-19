<?php

declare(strict_types = 1);

namespace Popo\Schema\Mapper;

interface SchemaMapperInterface
{
    /**
     * @param array<string> $mappings
     */
    public function mapKeyName(array $mappings, string $key): string;

    /**
     * @return array<string, string>
     */
    public function generateMappingPolicyPhpCode(): array;

    /**
     * @return array<string>
     */
    public function getSupportedMappingPolicies(): array;
}
