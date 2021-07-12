<?php

declare(strict_types = 1);

namespace App;

class AbstractExample implements ExampleInterface
{
    protected ?string $example;

    public function getExample(): ?string
    {
        return $this->example;
    }

    public function setExample(?string $example): self
    {
        $this->example = $example;

        return $this;
    }

    public function example(): void
    {
        $this->example = 'FooBar';
    }
}
