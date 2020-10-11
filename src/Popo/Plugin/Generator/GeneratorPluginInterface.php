<?php

declare(strict_types = 1);

namespace Popo\Plugin\Generator;

use Popo\Plugin\AcceptPatternInterface;
use Popo\Schema\Reader\PropertyInterface;
use Popo\Schema\Reader\SchemaInterface;

interface GeneratorPluginInterface extends AcceptPatternInterface
{
    /**
     * Specification:
     * - Uses property for which the content of string template will be generated.
     * - Generates string according to schema and configured plugins represented by string template.
     * - Returns generated string.
     *
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     * @param \Popo\Schema\Reader\PropertyInterface $property
     *
     * @return string
     */
    public function generate(SchemaInterface $schema, PropertyInterface $property): string;
}
