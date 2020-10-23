<?php declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\ReaderFactory;
use Popo\Schema\Reader\Schema;
use function str_replace;

class PropertyGenerator implements GeneratorInterface
{
    protected string $templateString;

    protected ReaderFactory $readerFactory;

    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected array $generatorPlugins = [];

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
        $generated = '';

        $propertyCollection = $this->readerFactory->createPropertyCollection($schema);

        foreach ($propertyCollection as $property) {
            $generated .= $this->generateMethodSignature($schema, $property, $this->templateString);
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
        }

        return $methodString;
    }
}
