<?php declare(strict_types = 1);

namespace Popo\Configurator;

use Popo\Configurator;

class ConfigurationItem
{
    protected ?string $schema;

    protected ?string $template;

    protected ?string $output;

    protected ?string $namespace;

    protected ?string $namespaceWithInterface = null;

    protected string $extension = '.php';

    protected ?string $returnType = null;

    protected ?string $extends = null;

    protected bool $abstract = false;

    protected bool $withPopo = true;

    protected bool $withInterface = false;

    protected ?Configurator $configurator;

    public function getSchema(): ?string
    {
        return $this->schema;
    }

    public function setSchema(?string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function setOutput(?string $output): self
    {
        $this->output = $output;

        return $this;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getNamespaceWithInterface(): ?string
    {
        return $this->namespaceWithInterface;
    }

    public function setNamespaceWithInterface(?string $namespaceWithInterface): self
    {
        $this->namespaceWithInterface = $namespaceWithInterface;

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getReturnType(): ?string
    {
        return $this->returnType;
    }

    public function setReturnType(?string $returnType): self
    {
        $this->returnType = $returnType;

        return $this;
    }

    public function getExtends(): ?string
    {
        return $this->extends;
    }

    public function setExtends(?string $extends): self
    {
        $this->extends = $extends;

        return $this;
    }

    public function isAbstract(): bool
    {
        return $this->abstract;
    }

    public function setAbstract(bool $abstract): self
    {
        $this->abstract = $abstract;

        return $this;
    }

    public function isWithPopo(): bool
    {
        return $this->withPopo;
    }

    public function setWithPopo(bool $withPopo): self
    {
        $this->withPopo = $withPopo;

        return $this;
    }

    public function isWithInterface(): bool
    {
        return $this->withInterface;
    }

    public function setWithInterface(bool $withInterface): self
    {
        $this->withInterface = $withInterface;

        return $this;
    }

    public function getConfigurator(): ?Configurator
    {
        return $this->configurator;
    }

    public function setConfigurator(?Configurator $configurator): self
    {
        $this->configurator = $configurator;

        return $this;
    }

    public function fromArray(array $data): self
    {
        $this->schema = $data['schema'] ?? null;
        $this->template = $data['template'] ?? null;
        $this->output = $data['output'] ?? null;
        $this->namespace = $data['namespace'] ?? null;
        $this->extension = $data['extension'] ?? '.php';
        $this->returnType = $data['returnType'] ?? null;
        $this->extends = $data['extends'] ?? null;
        $this->abstract = (bool) (int) $data['abstract'] ?? false;
        $this->withPopo = (bool) (int) $data['withPopo'] ?? false;
        $this->withInterface = (bool) (int) $data['withInterface'] ?? false;
        $this->configurator = $data['configurator'] ?? null;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'schema' => $this->schema,
            'template' => $this->template,
            'output' => $this->output,
            'namespace' => $this->namespace,
            'extension' => $this->extension,
            'returnType' => $this->returnType,
            'extends' => $this->extends,
            'abstract' => (bool) $this->abstract,
            'withPopo' => (bool) $this->withPopo,
            'withInterface' => (bool) $this->withInterface,
            'configurator' => $this->configurator,
        ];
    }
}
