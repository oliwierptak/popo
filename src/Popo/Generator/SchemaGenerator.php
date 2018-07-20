<?php

declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\SchemaInterface;

class SchemaGenerator implements GeneratorInterface
{
    const METHODS_PATTERN = '<<METHODS>>';

    /**
     * @var string
     */
    protected $templateString;

    /**
     * @var \Popo\Generator\GeneratorInterface
     */
    protected $propertyGenerator;

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected $generatorPlugins = [];

    /**
     * @param string $templateString
     * @param \Popo\Generator\GeneratorInterface $propertyGenerator
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $generatorPlugins
     */
    public function __construct(
        string $templateString,
        GeneratorInterface $propertyGenerator,
        array $generatorPlugins
    ) {
        $this->propertyGenerator = $propertyGenerator;
        $this->templateString = $templateString;
        $this->generatorPlugins = $generatorPlugins;
    }

    public function generate(SchemaInterface $schema): string
    {
        $generated = $this->generateSchemaString($schema);
        $generated = $this->generateMethodsPattern($schema, $generated);

        return $generated;
    }

    protected function generateSchemaString(SchemaInterface $schema): string
    {
        $generated = $this->templateString;

        foreach ($this->generatorPlugins as $pattern => $plugin) {
            if (!$plugin->acceptPattern($pattern)) {
                continue;
            }

            $expression = $plugin->generate($schema);
            $generated = \str_replace($pattern, $expression, $generated);
        }

        return $generated;
    }

    protected function generateMethodsPattern(SchemaInterface $schema, string $generated): string
    {
        $generated = \str_replace(
            static::METHODS_PATTERN,
            $this->propertyGenerator->generate($schema),
            $generated
        );

        return $generated;
    }
}
