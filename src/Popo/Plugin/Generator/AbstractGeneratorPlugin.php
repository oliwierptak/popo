<?php

declare(strict_types = 1);

namespace Popo\Plugin\Generator;

use Popo\Plugin\AcceptPatternInterface;
use Popo\Schema\Reader\PropertyExplorer;

abstract class AbstractGeneratorPlugin implements AcceptPatternInterface
{
    public const PATTERN = '<<UNDEFINED>>';

    protected PropertyExplorer $propertyExplorer;

    public function __construct(PropertyExplorer $propertyExplorer)
    {
        $this->propertyExplorer = $propertyExplorer;
    }

    public function acceptPattern(string $pattern): bool
    {
        return $pattern === static::PATTERN;
    }
}
