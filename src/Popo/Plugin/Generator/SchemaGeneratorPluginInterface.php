<?php declare(strict_types = 1);

namespace Popo\Plugin\Generator;

use Popo\Plugin\AcceptPatternInterface;
use Popo\Schema\Reader\Schema;

interface SchemaGeneratorPluginInterface extends AcceptPatternInterface
{
    /**
     * Specification:
     * - Uses schema for which the content of <<php.schema.tpl>> will be generated.
     * - Generates string according to schema and configured plugins represented by <<php.schema.tpl>> template.
     * - Returns generated string.
     *
     * @param Schema $schema
     *
     * @return string
     */
    public function generate(Schema $schema): string;
}
