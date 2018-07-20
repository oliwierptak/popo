<?php

declare(strict_types = 1);

namespace Popo\Generator;

interface GeneratorFactoryInterface
{
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
    ): GeneratorInterface;

    /**
     * @param string $propertyTemplateString
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $propertyPluginCollection
     *
     * @return \Popo\Generator\GeneratorInterface
     */
    public function createPropertyGenerator(string $propertyTemplateString, array $propertyPluginCollection): GeneratorInterface;
}
