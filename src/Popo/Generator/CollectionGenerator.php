<?php

declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\PropertyInterface;
use Popo\Schema\Reader\ReaderFactoryInterface;
use Popo\Schema\Reader\SchemaInterface;
use function str_replace;

class CollectionGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    protected $templateString;

    /**
     * @var \Popo\Schema\Reader\ReaderFactoryInterface
     */
    protected $readerFactory;

    /**
     * @var \Popo\Plugin\Generator\GeneratorPluginInterface[]
     */
    protected $generatorPlugins = [];

    /**
     * @var bool
     */
    protected $processed = false;

    /**
     * @param string $templateString
     * @param \Popo\Schema\Reader\ReaderFactoryInterface $readerFactory
     * @param \Popo\Plugin\Generator\GeneratorPluginInterface[] $generatorPlugins
     */
    public function __construct(string $templateString, ReaderFactoryInterface $readerFactory, array $generatorPlugins)
    {
        $this->templateString = $templateString;
        $this->readerFactory = $readerFactory;
        $this->generatorPlugins = $generatorPlugins;
    }

    public function generate(SchemaInterface $schema): string
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

    protected function generateMethodSignature(SchemaInterface $schema, PropertyInterface $property, string $methodString): string
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
