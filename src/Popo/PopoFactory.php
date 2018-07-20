<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Builder\BuilderFactory;
use Popo\Builder\BuilderFactoryInterface;
use Popo\Director\PopoDirector;
use Popo\Director\PopoDirectorInterface;
use Popo\Director\StringDirector;
use Popo\Director\StringDirectorInterface;
use Popo\Finder\FinderFactory;
use Popo\Finder\FinderFactoryInterface;
use Popo\Generator\GeneratorFactory;
use Popo\Generator\GeneratorFactoryInterface;
use Popo\Schema\Bundle\BundleSchemaFactory;
use Popo\Schema\Bundle\BundleSchemaFactoryInterface;
use Popo\Schema\Loader\LoaderFactory;
use Popo\Schema\Loader\LoaderFactoryInterface;
use Popo\Schema\Reader\ReaderFactory;
use Popo\Schema\Reader\ReaderFactoryInterface;
use Popo\Schema\SchemaFactory;
use Popo\Schema\SchemaFactoryInterface;
use Popo\Writer\WriterFactory;
use Popo\Writer\WriterFactoryInterface;

class PopoFactory implements PopoFactoryInterface
{
    public function createGeneratorFactory(): GeneratorFactoryInterface
    {
        return new GeneratorFactory(
            $this->createReaderFactory()
        );
    }

    public function createReaderFactory(): ReaderFactoryInterface
    {
        return new ReaderFactory();
    }

    public function createSchemaFactory(): SchemaFactoryInterface
    {
        return new SchemaFactory(
            $this->createFinderFactory(),
            $this->createLoaderFactory(),
            $this->createReaderFactory(),
            $this->createBundleSchemaFactory()
        );
    }

    public function createBundleSchemaFactory(): BundleSchemaFactoryInterface
    {
        return new BundleSchemaFactory();
    }

    public function createFinderFactory(): FinderFactoryInterface
    {
        return new FinderFactory();
    }

    public function createLoaderFactory(): LoaderFactoryInterface
    {
        return new LoaderFactory();
    }

    public function createWriterFactory(): WriterFactoryInterface
    {
        return new WriterFactory();
    }

    public function createBuilderFactory(): BuilderFactoryInterface
    {
        return new BuilderFactory(
            $this->createLoaderFactory(),
            $this->createGeneratorFactory(),
            $this->createSchemaFactory(),
            $this->createWriterFactory()
        );
    }

    public function createPopoDirector(): PopoDirectorInterface
    {
        return new PopoDirector(
            $this->createBuilderFactory(),
            $this->createSchemaFactory()
        );
    }

    public function createStringDirector(): StringDirectorInterface
    {
        return new StringDirector(
            $this->createBuilderFactory(),
            $this->createSchemaFactory()
        );
    }
}
