<?php

declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\ReaderFactoryInterface;

class GeneratorFactory implements GeneratorFactoryInterface
{
    /**
     * @var \Popo\Schema\Reader\ReaderFactoryInterface
     */
    protected $readerFactory;

    public function __construct(ReaderFactoryInterface $readerFactory)
    {
        $this->readerFactory = $readerFactory;
    }

    /**
     * @param string $schemaTemplateString
     * @param string $propertyTemplateString
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $schemaPluginCollection
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $propertyPluginCollection
     *
     * @return \Popo\Generator\GeneratorInterface
     */
    public function createSchemaGenerator(
        string $schemaTemplateString,
        string $propertyTemplateString,
        array $schemaPluginCollection,
        array $propertyPluginCollection
    ): GeneratorInterface {
        return new SchemaGenerator(
            $schemaTemplateString,
            $this->createPropertyGenerator($propertyTemplateString, $propertyPluginCollection),
            $schemaPluginCollection
        );
    }

    /**
     * @param string $propertyTemplateString
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $propertyPluginCollection
     *
     * @return \Popo\Generator\GeneratorInterface
     */
    public function createPropertyGenerator(string $propertyTemplateString, array $propertyPluginCollection): GeneratorInterface
    {
        return new PropertyGenerator(
            $propertyTemplateString,
            $this->readerFactory,
            $propertyPluginCollection
        );
    }
}
