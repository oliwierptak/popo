<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;

class DocblockTypeGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
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

    public function generate(Schema $schema, Property $property): string
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
