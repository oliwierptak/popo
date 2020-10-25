<?php declare(strict_types = 1);

namespace Popo\Plugin\Generator;

use Popo\Plugin\AcceptPatternInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;

interface PropertyGeneratorPluginInterface extends AcceptPatternInterface
{
    /**
     * Specification:
     * - Uses property for which the content of string template will be generated.
     * - Generates string according to schema and configured plugins represented by string template.
     * - Returns generated string.
     *
     * @param Schema $schema
     * @param Property $property
     *
     * @return string
     */
    public function generate(Schema $schema, Property $property): string;
}
