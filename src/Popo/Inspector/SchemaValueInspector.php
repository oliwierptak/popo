<?php

declare(strict_types = 1);

namespace Popo\Inspector;

use JetBrains\PhpStorm\Pure;
use function strpos;

class SchemaValueInspector
{
    #[Pure] public function isLiteral(mixed $value): bool
    {
        if (is_string($value) === false) {
            return false;
        }

        return strpos($value, '::') !== false || strpos($value, '::class') !== false;
    }
}
