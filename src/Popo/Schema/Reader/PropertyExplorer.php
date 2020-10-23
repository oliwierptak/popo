<?php declare(strict_types = 1);

namespace Popo\Schema\Reader;

use function in_array;
use function strcasecmp;
use function strtolower;

class PropertyExplorer
{
    public function isArray(string $type): bool
    {
        return strcasecmp($type, 'array') === 0;
    }

    public function isBoolean(string $type): bool
    {
        return strcasecmp($type, 'bool') === 0;
    }

    public function isFloat(string $type): bool
    {
        return strcasecmp($type, 'float') === 0;
    }

    public function isInt(string $type): bool
    {
        return strcasecmp($type, 'int') === 0;
    }

    public function isString(string $type): bool
    {
        return strcasecmp($type, 'string') === 0;
    }

    public function isMixed(string $type): bool
    {
        return strcasecmp($type, 'mixed') === 0;
    }

    public function hasTypeCast(string $type): bool
    {
        $supportedTypes = [
            'array',
            'bool',
            'float',
            'int',
            'string',
        ];

        return in_array(strtolower($type), $supportedTypes);
    }
}
