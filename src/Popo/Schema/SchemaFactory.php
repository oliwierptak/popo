<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Finder\FinderFactory;
use Popo\Schema\Bundle\BundleSchemaFactory;
use Popo\Schema\Loader\LoaderFactory;
use Popo\Schema\Reader\PropertyExplorer;
use Popo\Schema\Reader\ReaderFactory;
use Popo\Schema\Validator\SchemaValidator;

class SchemaFactory
{
    /**
     * @var \Popo\Schema\Bundle\BundleSchemaFactory
     */
    protected $bundleSchemaFactory;

    /**
     * @var \Popo\Finder\FinderFactory
     */
    protected $finderFactory;

    /**
     * @var \Popo\Schema\Loader\LoaderFactory
     */
    protected $loaderFactory;

    /**
     * @var \Popo\Schema\Reader\ReaderFactory
     */
    protected $readerFactory;

    public function __construct(
        FinderFactory $finderFactory,
        LoaderFactory $loaderFactory,
        ReaderFactory $readerFactory,
        BundleSchemaFactory $bundleSchemaFactory
    )
    {
        $this->finderFactory = $finderFactory;
        $this->loaderFactory = $loaderFactory;
        $this->readerFactory = $readerFactory;
        $this->bundleSchemaFactory = $bundleSchemaFactory;
    }

    public function createSchemaBuilder(): SchemaBuilder
    {
        return new SchemaBuilder(
            $this->finderFactory->createFileLoader(),
            $this->loaderFactory->createJsonLoader(),
            $this->readerFactory,
            $this->bundleSchemaFactory
        );
    }

    public function createSchemaMerger(): SchemaMerger
    {
        return new SchemaMerger(
            $this->createSchemaValidator(),
            $this->createSchemaBuilder()
        );
    }

    public function createSchemaValidator(): SchemaValidator
    {
        return new SchemaValidator();
    }

    public function createPropertyExplorer(): PropertyExplorer
    {
        return $this->readerFactory->createPropertyExplorer();
    }
}
