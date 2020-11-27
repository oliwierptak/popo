<?php declare(strict_types = 1);

namespace Popo\Configurator;

use LogicException;

class ConfigContainer
{
    protected string $configFilename;

    protected ?array $data = null;

    protected array $arguments = [];

    /**
     * @var ConfigurationItem[]
     */
    protected array $selectedItems = [];

    public function __construct(string $configFilename)
    {
        $this->configFilename = $configFilename;
    }

    public function getConfigByName(string $name): ?ConfigurationItem
    {
        $config = $this->getConfigItems()[$name] ?? null;

        if (!($config instanceof ConfigurationItem)) {
            throw new LogicException(
                sprintf(
                    'Unknown config section: "%s". Available sections: %s',
                    $name,
                    implode(', ', array_keys($this->getData()))
                )
            );
        }

        return $config;
    }

    /**
     * @return ConfigurationItem[]
     */
    public function getConfigItems(): array
    {
        $result = [];
        foreach ($this->getData() as $name => $data) {
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
        if (empty($this->data)) {
            $this->data = $this->loadConfig();
        }

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
    public function getSelectedItems(): array
    {
        return $this->selectedItems;
    }

    /**
     * @param ConfigurationItem[] $selectedItems
     *
     * @return $this
     */
    public function setSelectedItems(array $selectedItems): self
    {
        $this->selectedItems = $selectedItems;

        return $this;
    }

    protected function loadConfig(): array
    {
        if (!is_file($this->configFilename)) {
            throw new LogicException(
                sprintf(
                    'Config file: "%s" not found',
                    $this->configFilename
                )
            );
        }

        $data = parse_ini_file($this->configFilename, true) ?? [];
        if ($data === false) {
            $data = [];
        }

        return $data;
    }
}
