<?php

declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\ReaderFactory;
use Popo\Schema\Reader\Schema;

class ArrayableGenerator implements GeneratorInterface
{
    protected string $templateString;

    protected ReaderFactory $readerFactory;

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected array $generatorPlugins = [];

    /**
     * @param string $templateString
     * @param \Popo\Schema\Reader\ReaderFactory $readerFactory
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $generatorPlugins
     */
    public function __construct(string $templateString, ReaderFactory $readerFactory, array $generatorPlugins)
    {
        $this->templateString = $templateString;
        $this->readerFactory = $readerFactory;
        $this->generatorPlugins = $generatorPlugins;
    }

    public function generate(Schema $schema): string
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
