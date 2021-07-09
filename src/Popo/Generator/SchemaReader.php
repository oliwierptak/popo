<?php

declare(strict_types = 1);

namespace Popo\Generator;

use JetBrains\PhpStorm\Pure;
use Popo\PopoDefinesInterface;
use function strpos;

class SchemaReader
{
    #[Pure] public function isPopoProperty(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_POPO;
    }

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

    #[Pure] protected function hasConstString(string $value): bool
    {
        return strpos($value, '::') !== false && $this->hasClassString($value) === false;
    }

    protected function hasClassString(string $value): bool
    {
        return strpos($value, '::class') === true;
    }
}
