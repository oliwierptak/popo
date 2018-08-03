<?php

declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Builder\BuilderContainer;
use Popo\Schema\Reader\ReaderFactoryInterface;

class GeneratorFactory implements GeneratorFactoryInterface
{
    /**
     * @var \Popo\Schema\Reader\ReaderFactoryInterface
     */
    protected $readerFactory;

    public function __construct(ReaderFactoryInterface $readerFactory)
    {
        $this->readerFactory = $readerFactory;
    }

    public function createSchemaGenerator(BuilderContainer $container): GeneratorInterface
    {
        return new SchemaGenerator(
            $container->getSchemaTemplateString(),
            $this->createPropertyGenerator($container),
            $this->createCollectionGenerator($container),
            $container->getSchemaPluginCollection()
        );
    }

    public function createPropertyGenerator(BuilderContainer $container): GeneratorInterface
    {
        return new PropertyGenerator(
            $container->getPropertyTemplateString(),
            $this->readerFactory,
            $container->getPropertyPluginCollection()
        );
    }

    public function createCollectionGenerator(BuilderContainer $container): GeneratorInterface
    {
        return new CollectionGenerator(
            $container->getCollectionTemplateString(),
            $this->readerFactory,
            $container->getCollectionPluginCollection()
        );
    }
}
