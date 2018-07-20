<?php

declare(strict_types = 1);

namespace Popo\Plugin;

interface AcceptPatternInterface
{
    public function acceptPattern(string $pattern): bool;
}
