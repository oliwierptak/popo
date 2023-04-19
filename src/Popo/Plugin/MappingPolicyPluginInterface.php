<?php

declare(strict_types = 1);

namespace Popo\Plugin;

interface MappingPolicyPluginInterface
{
    public function run(string $key): string;
}
