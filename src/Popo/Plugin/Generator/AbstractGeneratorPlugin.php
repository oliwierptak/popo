<?php

declare(strict_types = 1);

namespace Popo\Plugin\Generator;

use Popo\Plugin\AcceptPatternInterface;
use Popo\Schema\Reader\PropertyExplorerInterface;

abstract class AbstractGeneratorPlugin implements AcceptPatternInterface
{
    const PATTERN = '<<UNDEFINED>>';

    /**
     * @var \Popo\Schema\Reader\PropertyExplorerInterface
     */
    protected $propertyExplorer;

    public function __construct(PropertyExplorerInterface $propertyExplorer)
    {
        $this->propertyExplorer = $propertyExplorer;
    }

    public function acceptPattern(string $pattern): bool
    {
        return $pattern === static::PATTERN;
    }
}
