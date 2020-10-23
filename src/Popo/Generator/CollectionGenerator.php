<?php declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\ReaderFactory;
use Popo\Schema\Reader\Schema;
use function str_replace;

class CollectionGenerator implements GeneratorInterface
{
    protected string $templateString;

    protected ReaderFactory $readerFactory;

    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected array $generatorPlugins = [];

    protected bool $processed = false;

    /**
     * @param string $templateString
     * @param \Popo\Schema\Reader\ReaderFactory $readerFactory
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $generatorPlugins
     */
    public function __construct(string $templateString, ReaderFactory $readerFactory, array $generatorPlugins)
    {
        $this->templateString = $templateString;
        $this->readerFactory = $readerFactory;
        $this->generatorPlugins = $generatorPlugins;
    }

    public function generate(Schema $schema): string
    {
        $this->processed = false;

        $propertyExplorer = $this->readerFactory->createPropertyExplorer();
        $generated = '';

        $propertyCollection = $this->readerFactory->createPropertyCollection($schema);

        foreach ($propertyCollection as $property) {
            if (!$propertyExplorer->isArray($property->getType())) {
                continue;
            }

            $generated .= $this->generateMethodSignature($schema, $property, $this->templateString);
        }

        if (!$this->processed) {
            return '';
        }

        return $generated;
    }

    protected function generateMethodSignature(Schema $schema, Property $property, string $methodString): string
    {
        foreach ($this->generatorPlugins as $pattern => $plugin) {
            if (!$plugin->acceptPattern($pattern)) {
                continue;
            }

            $expression = $plugin->generate($schema, $property);
            $methodString = str_replace($pattern, $expression, $methodString);

            $this->processed = true;
        }

        return $methodString;
    }
}
