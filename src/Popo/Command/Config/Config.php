<?php

declare(strict_types = 1);

namespace Popo\Command\Config;

class Config
{
    protected array $data = [];

    protected array $arguments = [];

    /**
     * @var Item[]
     */
    protected array $itemsToGenerate = [];

    public function getConfigByName(string $name): ?Item
    {
        return $this->getConfigItems()[$name] ?? null;
    }

    /**
     * @return Item[]
     */
    public function getConfigItems(): array
    {
        $result = [];
        foreach ($this->data as $name => $data) {
            $data = array_merge($this->getArguments(), $data);
            $config = (new Item())->fromArray($data);
            $result[$name] = $config;
        }

        return $result;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return Item[]
     */
    public function getItemsToGenerate(): array
    {
        return $this->itemsToGenerate;
    }

    /**
     * @param Item[] $itemsToGenerate
     *
     * @return $this
     */
    public function setItemsToGenerate(array $itemsToGenerate): self
    {
        $this->itemsToGenerate = $itemsToGenerate;
        return $this;
    }
}
