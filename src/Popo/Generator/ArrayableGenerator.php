<?php

declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\ReaderFactoryInterface;
use Popo\Schema\Reader\SchemaInterface;

class ArrayableGenerator implements GeneratorInterface
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
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected $generatorPlugins = [];

    /**
     * @param string $templateString
     * @param \Popo\Schema\Reader\ReaderFactoryInterface $readerFactory
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $generatorPlugins
     */
    public function __construct(string $templateString, ReaderFactoryInterface $readerFactory, array $generatorPlugins)
    {
        $this->templateString = $templateString;
        $this->readerFactory = $readerFactory;
        $this->generatorPlugins = $generatorPlugins;
    }

    public function generate(SchemaInterface $schema): string
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
}
