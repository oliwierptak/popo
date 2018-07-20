<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

interface PropertyExplorerInterface
{
    public function isArray(string $type): bool;

    public function isBoolean(string $type): bool;

    public function isFloat(string $type): bool;

    public function isInt(string $type): bool;

    public function isString(string $type): bool;

    public function isMixed(string $type): bool;

    public function hasTypeCast(string $type): bool;
}
