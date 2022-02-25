<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

class PopoBuilder extends AbstractBuilder
{
    protected function buildPhpFile(): self
    {
        $this->file = new PhpFile();

        return $this;
    }

    protected function buildNamespace(): self
    {
        $this->namespace = new PhpNamespace(
            $this->schema->getConfig()->getNamespace()
        );

        $this->file->addNamespace($this->namespace);

        return $this;
    }

    protected function buildProperties(): self
    {
        return $this;
    }

    protected function buildClass(): self
    {
        $this->class = $this->namespace->addClass($this->schema->getName());

        return $this;
    }
}
