<?php declare(strict_types = 1);

namespace Popo\Configurator;

class ConfigContainer
{
    protected array $data = [];

    protected array $arguments = [];

    /**
     * @var ConfigurationItem[]
     */
    protected array $itemsToGenerate = [];

    public function getConfigByName(string $name): ?ConfigurationItem
    {
        return $this->getConfigItems()[$name] ?? null;
    }

    /**
     * @return ConfigurationItem[]
     */
    public function getConfigItems(): array
    {
        $result = [];
        foreach ($this->data as $name => $data) {
            $data = array_merge($this->getArguments(), $data);
            $config = (new ConfigurationItem())->fromArray($data);
            $result[$name] = $config;
        }

        return $result;
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

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return ConfigurationItem[]
     */
    public function getItemsToGenerate(): array
    {
        return $this->itemsToGenerate;
    }

    /**
     * @param ConfigurationItem[] $itemsToGenerate
     *
     * @return $this
     */
    public function setItemsToGenerate(array $itemsToGenerate): self
    {
        $this->itemsToGenerate = $itemsToGenerate;

        return $this;
    }
}
