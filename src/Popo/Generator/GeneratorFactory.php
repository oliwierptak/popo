<?php declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Builder\BuilderContainer;
use Popo\Schema\Reader\ReaderFactory;

class GeneratorFactory
{
    protected ReaderFactory $readerFactory;

    public function __construct(ReaderFactory $readerFactory)
    {
        $this->readerFactory = $readerFactory;
    }

    public function createSchemaGenerator(BuilderContainer $container): SchemaGenerator
    {
        return new SchemaGenerator(
            $container->getSchemaTemplateString(),
            $this->createPropertyGenerator($container),
            $this->createCollectionGenerator($container),
            $container->getSchemaPluginCollection()
        );
    }

    public function createPropertyGenerator(BuilderContainer $container): PropertyGenerator
    {
        return new PropertyGenerator(
            $container->getPropertyTemplateString(),
            $this->readerFactory,
            $container->getPropertyPluginCollection()
        );
    }

    public function createCollectionGenerator(BuilderContainer $container): CollectionGenerator
    {
        return new CollectionGenerator(
            $container->getCollectionTemplateString(),
            $this->readerFactory,
            $container->getCollectionPluginCollection()
        );
    }
}
