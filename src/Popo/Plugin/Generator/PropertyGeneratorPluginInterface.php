<?php

declare(strict_types = 1);

namespace Popo\Plugin\Generator;

use Popo\Plugin\AcceptPatternInterface;
use Popo\Schema\Reader\PropertyInterface;

interface PropertyGeneratorPluginInterface extends AcceptPatternInterface
{
    /**
     * Specification:
     * - Uses property for which the content of <<php.property.tpl>> will be generated.
     * - Generates string according to schema and configured plugins represented by <<php.property.tpl>> template.
     * - Returns generated string.
     *
     * @param \Popo\Schema\Reader\PropertyInterface $property
     *
     * @return string
     */
    public function generate(PropertyInterface $property): string;
}
