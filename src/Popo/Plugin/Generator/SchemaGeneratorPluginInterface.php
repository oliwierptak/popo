<?php

declare(strict_types = 1);

namespace Popo\Plugin\Generator;

use Popo\Schema\Reader\SchemaInterface;

interface SchemaGeneratorPluginInterface
{
    /**
     * Specification:
     * - Uses schema for which the content of <<php.schema.tpl>> will be generated.
     * - Generates string according to schema and configured plugins represented by <<php.schema.tpl>> template.
     * - Returns generated string.
     *
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     *
     * @return string
     */
    public function generate(SchemaInterface $schema): string;
}
