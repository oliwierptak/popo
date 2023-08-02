<?php

declare(strict_types = 1);

namespace Popo\Schema\Generator;

use Nette\PhpGenerator\Attribute;
use Nette\PhpGenerator\Literal;
use Popo\Schema\Inspector\SchemaInspectorInterface;
use Popo\Schema\Property\Property;
use Popo\Schema\Schema;

class SchemaGenerator implements SchemaGeneratorInterface
{
    protected SchemaInspectorInterface $schemaInspector;

    public function __construct(SchemaInspectorInterface $schemaInspector)
    {
        $this->schemaInspector = $schemaInspector;
    }

    public function generateDefaultTypeValue(Property $property): mixed
    {
        $value = $property->getDefault();
        if (
            $this->schemaInspector->isPopoProperty($property->getType()) ||
            $this->schemaInspector->isDateTimeProperty($property->getType())
        ) {
            $value = null;
        }
        else {
            if ($this->schemaInspector->isLiteral($property->getDefault())) {
                $value = new Literal($property->getDefault());
            }
        }

        if ($this->schemaInspector->isBool($property->getType())) {
            $value = (bool)$value;
        }

        if ($this->schemaInspector->isArray($property->getType())) {
            $value = [];
            foreach ($property->getDefault() ?? [] as $key => $defaultValue) {
                if ($this->schemaInspector->isLiteral($defaultValue)) {
                    $defaultValue = new Literal($defaultValue);
                }

                $value[$key] = $defaultValue;
            }
        }

        return $value;
    }

    public function generatePopoType(Schema $schema, Property $property, bool $stripClass = true): string
    {
        if ($this->schemaInspector->isPopoProperty($property->getType())) {
            $namespace = $this->expandNamespaceForParameter($schema);

            $value = (string)$property->getDefault();
            $class = sprintf(
                '%s',
                $stripClass ? str_replace('::class', '', $value) : $value,
            );

            if ($value[0] !== '\\') {
                $class = sprintf(
                    '%s\\%s',
                    $namespace,
                    $stripClass ? str_replace('::class', '', $value) : $value,
                );
            }

            return $class;
        }

        if ($this->schemaInspector->isDateTimeProperty($property->getType())) {
            return '\DateTime';
        }

        return $property->getType();
    }

    public function generatePopoItemType(Schema $schema, Property $property): string
    {
        if ($this->schemaInspector->isLiteral($property->getItemType())) {
            $namespace = $this->expandNamespaceForParameter($schema);

            $value = (string)$property->getItemType();
            $class = sprintf(
                '%s',
                str_replace('::class', '', $value),
            );

            if ($value[0] !== '\\') {
                $class = sprintf(
                    '%s\\%s',
                    $namespace,
                    str_replace('::class', '', $value),
                );
            }

            return $class;
        }

        return (string)$property->getItemType();
    }

    /**
     * @param array<string, mixed> $attributes
     *
     * @return array<Attribute>
     */
    public function parseAttributes(?string $attribute, array $attributes): array
    {
        $result = [];
        if ($attribute) {
            $attributeValue = trim($attribute);
            $attributeValue = preg_replace('@[\[\];#]@i', '', $attributeValue);

            $tokens = preg_split('/\n/', (string)$attributeValue);
            foreach ($tokens as $token) {
                preg_match('@^([^(]+)\(([^)]+)\)$@im', $token, $attributeToken);
                if (empty($attributeToken)) {
                    $result[] = new Attribute($token, []);
                }
                else {
                    $attributeValue = $attributeToken[2] ?? [];
                    $attributeValue = new Literal($attributeValue);
                    $result[] = new Attribute($attributeToken[1], [$attributeValue]);
                }
            }
        }

        foreach ($attributes as $attributeData) {
            $attributeName = $attributeData['name'];
            $values = $attributeData['value'] ?? [];

            $attributeValue = [];
            foreach ($values as $name => $value) {
                if ($value === null) {
                    continue;
                }

                $attributeValue[] = new Literal($name . ": ?", [$value]);
            }

            $result[] = new Attribute($attributeName, $attributeValue);
        }

        return $result;
    }

    protected function expandNamespaceForParameter(Schema $schema): string
    {
        return sprintf(
            '\\%s',
            ltrim($schema->getConfig()->getNamespace(), '\\'),
        );
    }
}
