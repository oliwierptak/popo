<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Finder\FinderFactoryInterface;
use Popo\Schema\Bundle\BundleSchemaFactoryInterface;
use Popo\Schema\Loader\LoaderFactoryInterface;
use Popo\Schema\Reader\PropertyExplorerInterface;
use Popo\Schema\Reader\ReaderFactoryInterface;
use Popo\Schema\Validator\SchemaValidator;
use Popo\Schema\Validator\SchemaValidatorInterface;

class SchemaFactory implements SchemaFactoryInterface
{
    /**
     * @var \Popo\Schema\Bundle\BundleSchemaFactoryInterface
     */
    protected $bundleSchemaFactory;

    /**
     * @var \Popo\Finder\FinderFactoryInterface
     */
    protected $finderFactory;

    /**
     * @var \Popo\Schema\Loader\LoaderFactoryInterface
     */
    protected $loaderFactory;

    /**
     * @var \Popo\Schema\Reader\ReaderFactoryInterface
     */
    protected $readerFactory;

    public function __construct(
        FinderFactoryInterface $finderFactory,
        LoaderFactoryInterface $loaderFactory,
        ReaderFactoryInterface $readerFactory,
        BundleSchemaFactoryInterface $bundleSchemaFactory
    ) {
        $this->finderFactory = $finderFactory;
        $this->loaderFactory = $loaderFactory;
        $this->readerFactory = $readerFactory;
        $this->bundleSchemaFactory = $bundleSchemaFactory;
    }

    public function createSchemaBuilder(): SchemaBuilderInterface
    {
        return new SchemaBuilder(
            $this->finderFactory->createFileLoader(),
            $this->loaderFactory->createJsonLoader(),
            $this->readerFactory,
            $this->bundleSchemaFactory
        );
    }

    public function createSchemaMerger(): SchemaMergerInterface
    {
        return new SchemaMerger(
            $this->createSchemaValidator(),
            $this->createSchemaBuilder()
        );
    }

    public function createSchemaValidator(): SchemaValidatorInterface
    {
        return new SchemaValidator();
    }

    public function createPropertyExplorer(): PropertyExplorerInterface
    {
        return $this->readerFactory->createPropertyExplorer();
    }
}
