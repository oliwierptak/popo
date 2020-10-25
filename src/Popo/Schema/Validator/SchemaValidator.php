<?php declare(strict_types = 1);

namespace Popo\Schema\Validator;

use LogicException;
use Popo\Schema\Bundle\BundleSchema;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;
use Popo\Schema\Validator\Exception\NotBundleSchemaException;
use Popo\Schema\Validator\Exception\NotUniquePropertyException;
use function sprintf;
use function strcasecmp;

class SchemaValidator
{
    public function assertIsBundleSchema(BundleSchema $bundleSchema): void
    {
        if (!$bundleSchema->isBundleSchema()) {
            throw new NotBundleSchemaException(
                sprintf(
                    'Schema: "%s" is not bundle schema',
                    $bundleSchema->getSchemaFilename()->getPathname()
                )
            );
        }
    }

    public function assertProperties(
        BundleSchema $bundleSchema,
        array $bundleSchemaProperties,
        BundleSchema $additionalBundleSchema,
        array $additionalProperties
    ): void {
        foreach ($bundleSchemaProperties as $bundleSchemaProperty) {
            $this->assertUniqueProperties(
                $bundleSchemaProperty,
                $bundleSchema,
                $additionalBundleSchema,
                $additionalProperties
            );
        }
    }

    /**
     * @param \Popo\Schema\Reader\Property $bundleSchemaProperty
     * @param \Popo\Schema\Bundle\BundleSchema $bundleSchema
     * @param \Popo\Schema\Bundle\BundleSchema $additionalBundleSchema
     * @param array $additionalProperties
     *
     * @return void
     * @throws \Popo\Schema\Validator\Exception\NotUniquePropertyException
     *
     */
    protected function assertUniqueProperties(
        Property $bundleSchemaProperty,
        BundleSchema $bundleSchema,
        BundleSchema $additionalBundleSchema,
        array $additionalProperties
    ): void {
        foreach ($additionalProperties as $additionalSchemaProperty) {
            if (strcasecmp($bundleSchemaProperty->getName(), $additionalSchemaProperty->getName()) === 0) {
                throw new NotUniquePropertyException(
                    sprintf(
                        'The property: "%s" is already defined in "%s" and cannot be redefined in "%s"',
                        $bundleSchemaProperty->getName(),
                        $bundleSchema->getSchemaFilename()->getPathname(),
                        $additionalBundleSchema->getSchemaFilename()->getPathname()
                    )
                );
            }
        }
    }

    /**
     * @param \Popo\Schema\Reader\Schema $schema
     *
     * @return void
     * @throws \LogicException
     */
    public function assertExtends(Schema $schema): void
    {
        $extends = trim($schema->getExtends());
        if ($extends === '') {
            return;
        }

        $tokens = explode('\\', $schema->getExtends());
        array_pop($tokens);

        if (empty($tokens)) {
            throw new LogicException(
                sprintf(
                    'Schema "%s" expects Fully Qualified Class Name (FQCN) for "extends" option. Got "%s" instead.',
                    $schema->getName(),
                    $extends
                )
            );
        }

        $parentNamespace = implode('\\', $tokens);
        $parentNamespace = ltrim($parentNamespace, '\\');
        $parentNamespace = rtrim($parentNamespace, '\\');

        if (strcasecmp($schema->getNamespace(), $parentNamespace) === 0) {
            throw new LogicException(
                sprintf(
                    'Class "%s" cannot be used to extend schema "%s" because they both seem to be POPO objects. ' .
                    'Please move the parent class out of POPO namespace or define class "%s" as abstract.',
                    $extends,
                    $schema->getName(),
                    $schema->getName()
                )
            );
        }
    }
}
