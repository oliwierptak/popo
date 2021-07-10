<?php

declare(strict_types = 1);

namespace Popo\Inspector;

use JetBrains\PhpStorm\Pure;
use function strpos;

class SchemaValueInspector
{
    public function isConstValue(mixed $value): bool
    {
        if (is_string($value) === false) {
            return false;
        }

        $hasConstString = $this->hasConstString($value);

        return $hasConstString && $value[0] === '\\' && ctype_upper($value[1]);
    }

    public function isClassNameValue(mixed $value): bool
    {
        if (is_string($value) === false) {
            return false;
        }

        return ctype_upper($value[0]) && $this->hasClassString($value);
    }

    public function isFqcn(mixed $value): bool
    {
        if (is_string($value) === false) {
            return false;
        }

        return $value[0] === '\\' && ctype_upper($value[1]) && $this->hasClassString($value);
    }

    #[Pure] protected function hasConstString(mixed $value): bool
    {
        if (is_string($value) === false) {
            return false;
        }

        return strpos($value, '::') !== false && $this->hasClassString($value) === false;
    }

    protected function hasClassString(string $value): bool
    {
        return strpos($value, '::class') === true;
    }
}
