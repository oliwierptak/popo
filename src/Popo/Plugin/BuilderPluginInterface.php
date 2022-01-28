<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Popo\Schema\Generator\SchemaGeneratorInterface;
use Popo\Schema\Inspector\SchemaInspectorInterface;
use Popo\Schema\Schema;

interface BuilderPluginInterface
{
    public function getSchema(): Schema;

    public function getFile(): PhpFile;

    public function getNamespace(): PhpNamespace;

    public function getClass(): ClassType;

    public function getSchemaInspector(): SchemaInspectorInterface;

    public function getSchemaGenerator(): SchemaGeneratorInterface;
}
