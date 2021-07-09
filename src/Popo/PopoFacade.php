<?php

declare(strict_types = 1);

namespace Popo;

class PopoFacade
{
    protected PopoFactory $factory;

    public function getFactory(): PopoFactory
    {
        if (empty($this->factory)) {
            $this->factory = new PopoFactory();
        }

        return $this->factory;
    }

    public function setFactory(PopoFactory $factory): self
    {
        $this->factory = $factory;

        return $this;
    }

    public function generate(array $files): void
    {
        $this->getFactory()
            ->createPopoModel()
            ->generate($files);
    }
}
