<?php declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Schema\Bundle\BundleSchema;
use Popo\Schema\Validator\SchemaValidator;
use function array_merge;
use function array_shift;
use function array_values;
use function uksort;

class SchemaMerger
{
    protected SchemaValidator $schemaValidator;

    protected SchemaBuilder $schemaBuilder;

    public function __construct(SchemaValidator $schemaValidator, SchemaBuilder $schemaBuilder)
    {
        $this->schemaValidator = $schemaValidator;
        $this->schemaBuilder = $schemaBuilder;
    }

    /**
     * @param \Popo\Schema\Bundle\BundleSchema[] $bundleSchemaCollection
     *
     * @return \Popo\Schema\Bundle\BundleSchema[]
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

        $collectionToMerge = $this->setupHierarchy($collectionToMerge);
        /** @var BundleSchema[] $schemaFiles */
        foreach ($collectionToMerge as $name => $schemaFiles) {
            $schemaFiles = $this->sortByBundleSchema($schemaFiles);
            $bundleSchema = array_shift($schemaFiles);
            $this->schemaValidator->assertIsBundleSchema($bundleSchema);

            $properties = $this->mergeProperties($bundleSchema, $schemaFiles);
            $mergedBundleSchema = $this->schemaBuilder->buildBundleSchemaWithProperties($bundleSchema, $properties);

            $result[$mergedBundleSchema->getSchema()->getName()] = $mergedBundleSchema;
        }

        return $result;
    }

    /**
     * @param \Popo\Schema\Bundle\BundleSchema[] $bundleSchemaCollection
     *
     * @return \Popo\Schema\Bundle\BundleSchema[]
     */
    protected function setupHierarchy(array $bundleSchemaCollection): array
    {
        /** @var BundleSchema[] $schemaCollection */
        foreach ($bundleSchemaCollection as $name => $schemaCollection) {
            foreach ($schemaCollection as $bundleSchema) {
                $parent = $bundleSchema->getSchema()->getExtends();
                if ($parent !== '' && \array_key_exists($parent, $bundleSchemaCollection)) {
                    $bundleSchema->getSchema()->setParent($bundleSchema->getSchema());
                }
            }
        }

        return $bundleSchemaCollection;
    }

    /**
     * @param \Popo\Schema\Bundle\BundleSchema[] $schemaFiles
     *
     * @return \Popo\Schema\Bundle\BundleSchema[]
     */
    protected function sortByBundleSchema(array $schemaFiles): array
    {
        uksort(
            $schemaFiles,
            static function ($a, $b) use ($schemaFiles) {
                $aSchema = $schemaFiles[$a];
                $bSchema = $schemaFiles[$b];

                return $aSchema->isBundleSchema() < $bSchema->isBundleSchema() ? 1 : 0;
            }
        );

        return array_values($schemaFiles);
    }

    /**
     * @param \Popo\Schema\Bundle\BundleSchema $bundleSchema
     * @param array $additionalBundleSchemaCollection
     *
     * @return \Popo\Schema\Reader\Property[]
     */
    protected function mergeProperties(BundleSchema $bundleSchema, array $additionalBundleSchemaCollection): array
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

            $propertiesToMerge = array_merge(
                $propertiesToMerge,
                $additionalProperties
            );
        }

        $bundleSchemaProperties = array_merge($bundleSchemaProperties, $propertiesToMerge);

        return $bundleSchemaProperties;
    }
}
