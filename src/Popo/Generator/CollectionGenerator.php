<?php

declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\PropertyInterface;
use Popo\Schema\Reader\ReaderFactoryInterface;
use Popo\Schema\Reader\SchemaInterface;

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
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected $generatorPlugins = [];

    /**
     * @var bool
     */
    protected $processed = false;

    /**
     * @param string $templateString
     * @param \Popo\Schema\Reader\ReaderFactoryInterface $readerFactory
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $generatorPlugins
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

            $generated .= $this->generateMethodSignature($property, $this->templateString);
        }

        if (!$this->processed) {
            return '';
        }

        return $generated;
    }

    protected function generateMethodSignature(PropertyInterface $property, string $methodString): string
    {
        foreach ($this->generatorPlugins as $pattern => $plugin) {
            if (!$plugin->acceptPattern($pattern)) {
                continue;
            }

            $expression = $plugin->generate($property);
            $methodString = \str_replace($pattern, $expression, $methodString);

            $this->processed = true;
        }

        return $methodString;
    }
}