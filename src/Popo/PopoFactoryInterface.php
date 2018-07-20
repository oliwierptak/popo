<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Builder\BuilderFactoryInterface;
use Popo\Director\PopoDirectorInterface;
use Popo\Director\StringDirectorInterface;
use Popo\Finder\FinderFactoryInterface;
use Popo\Generator\GeneratorFactoryInterface;
use Popo\Schema\Bundle\BundleSchemaFactoryInterface;
use Popo\Schema\Loader\LoaderFactoryInterface;
use Popo\Schema\Reader\ReaderFactoryInterface;
use Popo\Schema\SchemaFactoryInterface;
use Popo\Writer\WriterFactoryInterface;

interface PopoFactoryInterface
{
    public function createGeneratorFactory(): GeneratorFactoryInterface;

    public function createReaderFactory(): ReaderFactoryInterface;

    public function createSchemaFactory(): SchemaFactoryInterface;

    public function createBundleSchemaFactory(): BundleSchemaFactoryInterface;

    public function createFinderFactory(): FinderFactoryInterface;

    public function createLoaderFactory(): LoaderFactoryInterface;

    public function createWriterFactory(): WriterFactoryInterface;

    public function createBuilderFactory(): BuilderFactoryInterface;

    public function createPopoDirector(): PopoDirectorInterface;

    public function createStringDirector(): StringDirectorInterface;
}
