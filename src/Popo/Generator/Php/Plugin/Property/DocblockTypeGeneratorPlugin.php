<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;
use Popo\Schema\Reader\SchemaInterface;

class DocblockTypeGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<DOCBLOCK_TYPE>>';

    /**
     * @var array
     */
    protected $docblockTypes = [
        'int' => 'integer',
        'string' => 'string',
        'bool' => 'boolean',
        'float' => 'float',
        'array' => 'array',
    ];

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        return $this->typeToDocblockType($property->getType());
    }

    protected function typeToDocblockType(string $type): string
    {
        if (!isset($this->docblockTypes[$type])) {
            return $type;
        }

        return $this->docblockTypes[$type];
    }
}
