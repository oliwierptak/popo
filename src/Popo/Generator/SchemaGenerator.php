<?php declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\Schema;

class SchemaGenerator implements GeneratorInterface
{
    protected const METHODS_PATTERN = '<<METHODS>>';
    protected const COLLECTION_PATTERN = '<<COLLECTION>>';

    protected string $templateString;

    protected PropertyGenerator $propertyGenerator;

    protected CollectionGenerator $collectionGenerator;

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected array $generatorPlugins = [];

    public function __construct(
        string $templateString,
        PropertyGenerator $propertyGenerator,
        CollectionGenerator $collectionGenerator,
        array $generatorPlugins
    ) {
        $this->propertyGenerator = $propertyGenerator;
        $this->collectionGenerator = $collectionGenerator;
        $this->templateString = $templateString;
        $this->generatorPlugins = $generatorPlugins;
    }

    public function generate(Schema $schema): string
    {
        $generated = $this->generateSchemaString($schema);
        $generated = $this->generateMethodsPattern($schema, $generated);
        $generated = $this->generateCollectionPattern($schema, $generated);

        return $generated;
    }

    protected function generateSchemaString(Schema $schema): string
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

    protected function generateMethodsPattern(Schema $schema, string $generated): string
    {
        $generated = \str_replace(
            static::METHODS_PATTERN,
            $this->propertyGenerator->generate($schema),
            $generated
        );

        return $generated;
    }

    protected function generateCollectionPattern(Schema $schema, string $generated): string
    {
        $collectionString = $this->collectionGenerator->generate($schema);
        $generated = \str_replace(
            static::COLLECTION_PATTERN,
            $collectionString,
            $generated
        );

        return $generated;
    }
}
