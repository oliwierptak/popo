<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Popo\Schema\Schema;
use Popo\Schema\SchemaInspector;

interface BuilderPluginInterface
{
    public function getSchema(): Schema;

    public function getFile(): PhpFile;

    public function getNamespace(): PhpNamespace;

    public function getClass(): ClassType;

    public function getSchemaInspector(): SchemaInspector;
}
