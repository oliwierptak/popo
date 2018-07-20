<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Schema\Bundle\BundleSchemaInterface;
use Popo\Schema\Validator\SchemaValidatorInterface;

class SchemaMerger implements SchemaMergerInterface
{
    /**
     * @var \Popo\Schema\Validator\SchemaValidatorInterface
     */
    protected $schemaValidator;

    /**
     * @var \Popo\Schema\SchemaBuilderInterface
     */
    protected $schemaBuilder;

    public function __construct(SchemaValidatorInterface $schemaValidator, SchemaBuilderInterface $schemaBuilder)
    {
        $this->schemaValidator = $schemaValidator;
        $this->schemaBuilder = $schemaBuilder;
    }

    /**
     * @param array $bundleSchemaCollection
     *
     * @return \Popo\Schema\Bundle\BundleSchemaInterface[]
     */
    public function merge(array $bundleSchemaCollection): array
    {
        $result = [];
        $collectionToMerge = [];

        foreach ($bundleSchemaCollection as $schemaFilename => $bundleSchemaData) {
            foreach ($bundleSchemaData as $path => $bundlesSchemaFiles) {
                foreach ($bundlesSchemaFiles as $name => $bundleSchemaFile) {
                    $collectionToMerge[$name][] = $bundleSchemaFile;
                }
            }
        }

        foreach ($collectionToMerge as $name => $data) {
            $schemaFiles = $this->sortByBundleSchema($data);
            $bundleSchema = \array_shift($schemaFiles);
            $this->schemaValidator->assertIsBundleSchema($bundleSchema);

            $mergedProperties = $this->mergeProperties($bundleSchema, $schemaFiles);
            $mergedBundleSchema = $this->schemaBuilder->buildBundleSchemaWithProperties($bundleSchema, $mergedProperties);

            $result[$mergedBundleSchema->getSchema()->getName()] = $mergedBundleSchema;
        }

        return $result;
    }

    /**
     * @param \Popo\Schema\Bundle\BundleSchemaInterface $bundleSchema
     * @param array $additionalBundleSchemaCollection
     *
     * @return array
     */
    protected function mergeProperties(BundleSchemaInterface $bundleSchema, array $additionalBundleSchemaCollection): array
    {
        $bundleSchemaProperties = $this->schemaBuilder->buildProperties($bundleSchema->getSchema());

        $propertiesToMerge = [];
        foreach ($additionalBundleSchemaCollection as $additionalBundleSchema) {
            $additionalProperties = $this->schemaBuilder->buildProperties(
                $additionalBundleSchema->getSchema()
            );

            $this->schemaValidator->assertProperties(
                $bundleSchema,
                $bundleSchemaProperties,
                $additionalBundleSchema,
                $additionalProperties
            );

            $propertiesToMerge = \array_merge(
                $propertiesToMerge,
                $additionalProperties
            );
        }

        $bundleSchemaProperties = \array_merge($bundleSchemaProperties, $propertiesToMerge);

        return $bundleSchemaProperties;
    }


    /**
     * @param \Popo\Schema\Bundle\BundleSchemaInterface[] $schemaFiles
     *
     * @return \Popo\Schema\Bundle\BundleSchemaInterface[]
     */
    protected function sortByBundleSchema(array $schemaFiles): array
    {
        \uksort($schemaFiles, function ($a, $b) use ($schemaFiles) {
            $aSchema = $schemaFiles[$a];
            $bSchema = $schemaFiles[$b];

            return $aSchema->isBundleSchema() < $bSchema->isBundleSchema();
        });

        return \array_values($schemaFiles);
    }
}
