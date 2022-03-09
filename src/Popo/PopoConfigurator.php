<?php

namespace Popo;

use UnexpectedValueException;

class PopoConfigurator
{
    protected const METADATA = [
        'schemaPath' => ['type' => 'string', 'default' => null],
        'namespace' => ['type' => 'string', 'default' => null],
        'namespaceRoot' => ['type' => 'string', 'default' => null],
        'outputPath' => ['type' => 'string', 'default' => null],
        'phpFilePluginCollection' => [
            'type' => 'array',
            'default' => [
                \Popo\Plugin\PhpFilePlugin\StrictTypesPhpFilePlugin::class,
                \Popo\Plugin\PhpFilePlugin\CommentPhpFilePlugin::class,
            ],
        ],
        'namespacePluginCollection' => [
            'type' => 'array',
            'default' => [\Popo\Plugin\NamespacePlugin\UseUnexpectedValueExceptionPlugin::class],
        ],
        'classPluginCollection' => [
            'type' => 'array',
            'default' => [
                \Popo\Plugin\ClassPlugin\DateTimeMethodClassPlugin::class,
                \Popo\Plugin\ClassPlugin\ExtendClassPlugin::class,
                \Popo\Plugin\ClassPlugin\FromArrayClassPlugin::class,
                \Popo\Plugin\ClassPlugin\ImplementClassPlugin::class,
                \Popo\Plugin\ClassPlugin\IsNewClassPlugin::class,
                \Popo\Plugin\ClassPlugin\ListModifiedPropertiesClassPlugin::class,
                \Popo\Plugin\ClassPlugin\MetadataClassPlugin::class,
                \Popo\Plugin\ClassPlugin\ModifiedToArrayClassPlugin::class,
                \Popo\Plugin\ClassPlugin\PopoMethodClassPlugin::class,
                \Popo\Plugin\ClassPlugin\RequireAllClassPlugin::class,
                \Popo\Plugin\ClassPlugin\ToArrayClassPlugin::class,
                \Popo\Plugin\ClassPlugin\UpdateMapClassPlugin::class,
            ],
        ],
        'propertyPluginCollection' => [
            'type' => 'array',
            'default' => [
                \Popo\Plugin\PropertyPlugin\AddItemPropertyMethodPlugin::class,
                \Popo\Plugin\PropertyPlugin\DefinePropertyPlugin::class,
                \Popo\Plugin\PropertyPlugin\GetPropertyMethodPlugin::class,
                \Popo\Plugin\PropertyPlugin\HasPropertyMethodPlugin::class,
                \Popo\Plugin\PropertyPlugin\RequirePropertyMethodPlugin::class,
                \Popo\Plugin\PropertyPlugin\SetPropertyMethodPlugin::class,
            ],
        ],
        'schemaConfigFilename' => ['type' => 'string', 'default' => null],
        'schemaPathFilter' => ['type' => 'string', 'default' => null],
        'schemaFilenameMask' => ['type' => 'string', 'default' => '*.popo.yml'],
        'shouldIgnoreNonExistingSchemaFolder' => ['type' => 'bool', 'default' => false],
    ];

    protected array $updateMap = [];
    protected ?string $schemaPath = null;
    protected ?string $namespace = null;
    protected ?string $namespaceRoot = null;
    protected ?string $outputPath = null;

    protected array $phpFilePluginCollection = [
        \Popo\Plugin\PhpFilePlugin\StrictTypesPhpFilePlugin::class,
        \Popo\Plugin\PhpFilePlugin\CommentPhpFilePlugin::class,
    ];

    protected array $namespacePluginCollection = [\Popo\Plugin\NamespacePlugin\UseUnexpectedValueExceptionPlugin::class];

    protected array $classPluginCollection = [
        \Popo\Plugin\ClassPlugin\DateTimeMethodClassPlugin::class,
        \Popo\Plugin\ClassPlugin\ExtendClassPlugin::class,
        \Popo\Plugin\ClassPlugin\FromArrayClassPlugin::class,
        \Popo\Plugin\ClassPlugin\ImplementClassPlugin::class,
        \Popo\Plugin\ClassPlugin\IsNewClassPlugin::class,
        \Popo\Plugin\ClassPlugin\ListModifiedPropertiesClassPlugin::class,
        \Popo\Plugin\ClassPlugin\MetadataClassPlugin::class,
        \Popo\Plugin\ClassPlugin\ModifiedToArrayClassPlugin::class,
        \Popo\Plugin\ClassPlugin\PopoMethodClassPlugin::class,
        \Popo\Plugin\ClassPlugin\RequireAllClassPlugin::class,
        \Popo\Plugin\ClassPlugin\ToArrayClassPlugin::class,
        \Popo\Plugin\ClassPlugin\UpdateMapClassPlugin::class,
    ];

    protected array $propertyPluginCollection = [
        \Popo\Plugin\PropertyPlugin\AddItemPropertyMethodPlugin::class,
        \Popo\Plugin\PropertyPlugin\DefinePropertyPlugin::class,
        \Popo\Plugin\PropertyPlugin\GetPropertyMethodPlugin::class,
        \Popo\Plugin\PropertyPlugin\HasPropertyMethodPlugin::class,
        \Popo\Plugin\PropertyPlugin\RequirePropertyMethodPlugin::class,
        \Popo\Plugin\PropertyPlugin\SetPropertyMethodPlugin::class,
    ];

    protected ?string $schemaConfigFilename = null;
    protected ?string $schemaPathFilter = null;
    protected ?string $schemaFilenameMask = '*.popo.yml';
    protected bool $shouldIgnoreNonExistingSchemaFolder = false;

    protected function setupDateTimeProperty($propertyName): void
    {
        if (static::METADATA[$propertyName]['type'] === 'datetime' && $this->$propertyName === null) {
            $value = static::METADATA[$propertyName]['default'];
            $datetime = new \DateTime($value);
            $timezone = static::METADATA[$propertyName]['timezone'] ?? null;
            if ($timezone !== null) {
                $timezone = new \DateTimeZone($timezone);
                $datetime->setTimezone($timezone);
            }
            $this->$propertyName = $datetime;
        }
    }

    public function fromArray(array $data): self
    {
        foreach (static::METADATA as $name => $meta) {
            $value = $data[$name] ?? $this->$name ?? null;
            $popoValue = $meta['default'];

            if ($popoValue !== null && $meta['type'] === 'popo') {
                $popo = new $popoValue;

                if (is_array($value)) {
                    $popo->fromArray($value);
                }

                $value = $popo;
            }

            if ($meta['type'] === 'datetime') {
                if (($value instanceof \DateTime) === false) {
                    $datetime = new \DateTime($data[$name] ?? $meta['default']);
                    $timezone = $meta['extra']['timezone'] ?? null;
                    if ($timezone !== null) {
                        $timezone = new \DateTimeZone($timezone);
                        $datetime->setTimezone($timezone);
                    }
                    $value = $datetime;
                }
            }

            $this->$name = $value;
            if (\array_key_exists($name, $data)) {
                $this->updateMap[$name] = true;
            }
        }

        return $this;
    }

    public function isNew(): bool
    {
        return empty($this->updateMap) === true;
    }

    public function listModifiedProperties(): array
    {
        $sorted = \array_keys($this->updateMap);
        sort($sorted, \SORT_STRING);
        return $sorted;
    }

    public function modifiedToArray(): array
    {
        $data = $this->toArray();
        $modifiedProperties = $this->listModifiedProperties();

        return \array_filter($data, function ($key) use ($modifiedProperties) {
            return \in_array($key, $modifiedProperties);
        }, \ARRAY_FILTER_USE_KEY);
    }

    protected function setupPopoProperty($propertyName): void
    {
        if (static::METADATA[$propertyName]['type'] === 'popo' && $this->$propertyName === null) {
            $popo = static::METADATA[$propertyName]['default'];
            $this->$propertyName = new $popo;
        }
    }

    public function requireAll(): self
    {
        $errors = [];

        try {
            $this->requireSchemaPath();
        }
        catch (\Throwable $throwable) {
            $errors['schemaPath'] = $throwable->getMessage();
        }
        try {
            $this->requireNamespace();
        }
        catch (\Throwable $throwable) {
            $errors['namespace'] = $throwable->getMessage();
        }
        try {
            $this->requireNamespaceRoot();
        }
        catch (\Throwable $throwable) {
            $errors['namespaceRoot'] = $throwable->getMessage();
        }
        try {
            $this->requireOutputPath();
        }
        catch (\Throwable $throwable) {
            $errors['outputPath'] = $throwable->getMessage();
        }
        try {
            $this->requirePhpFilePluginCollection();
        }
        catch (\Throwable $throwable) {
            $errors['phpFilePluginCollection'] = $throwable->getMessage();
        }
        try {
            $this->requireNamespacePluginCollection();
        }
        catch (\Throwable $throwable) {
            $errors['namespacePluginCollection'] = $throwable->getMessage();
        }
        try {
            $this->requireClassPluginCollection();
        }
        catch (\Throwable $throwable) {
            $errors['classPluginCollection'] = $throwable->getMessage();
        }
        try {
            $this->requirePropertyPluginCollection();
        }
        catch (\Throwable $throwable) {
            $errors['propertyPluginCollection'] = $throwable->getMessage();
        }
        try {
            $this->requireSchemaConfigFilename();
        }
        catch (\Throwable $throwable) {
            $errors['schemaConfigFilename'] = $throwable->getMessage();
        }
        try {
            $this->requireSchemaPathFilter();
        }
        catch (\Throwable $throwable) {
            $errors['schemaPathFilter'] = $throwable->getMessage();
        }
        try {
            $this->requireSchemaFilenameMask();
        }
        catch (\Throwable $throwable) {
            $errors['schemaFilenameMask'] = $throwable->getMessage();
        }
        try {
            $this->requireShouldIgnoreNonExistingSchemaFolder();
        }
        catch (\Throwable $throwable) {
            $errors['shouldIgnoreNonExistingSchemaFolder'] = $throwable->getMessage();
        }

        if (empty($errors) === false) {
            throw new UnexpectedValueException(
                implode("\n", $errors)
            );
        }

        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'schemaPath' => $this->schemaPath,
            'namespace' => $this->namespace,
            'namespaceRoot' => $this->namespaceRoot,
            'outputPath' => $this->outputPath,
            'phpFilePluginCollection' => $this->phpFilePluginCollection,
            'namespacePluginCollection' => $this->namespacePluginCollection,
            'classPluginCollection' => $this->classPluginCollection,
            'propertyPluginCollection' => $this->propertyPluginCollection,
            'schemaConfigFilename' => $this->schemaConfigFilename,
            'schemaPathFilter' => $this->schemaPathFilter,
            'schemaFilenameMask' => $this->schemaFilenameMask,
            'shouldIgnoreNonExistingSchemaFolder' => $this->shouldIgnoreNonExistingSchemaFolder,
        ];

        array_walk(
            $data,
            function (&$value, $name) use ($data) {
                if (static::METADATA[$name]['type'] === 'popo') {
                    $popo = static::METADATA[$name]['default'];
                    $value = $this->$name !== null ? $this->$name->toArray() : (new $popo)->toArray();
                }

                if (static::METADATA[$name]['type'] === 'datetime') {
                    if (($value instanceof \DateTime) === false) {
                        $datetime = new \DateTime(static::METADATA[$name]['default']);
                        $timezone = static::METADATA[$name]['extra']['timezone'] ?? null;
                        if ($timezone !== null) {
                            $timezone = new \DateTimeZone($timezone);
                            $datetime->setTimezone($timezone);
                        }
                        $value = $datetime;
                    }

                    $value = $value->format(static::METADATA[$name]['format']);
                }
            }
        );

        return $data;
    }

    public function getSchemaPath(): ?string
    {
        return $this->schemaPath;
    }

    public function hasSchemaPath(): bool
    {
        return $this->schemaPath !== null;
    }

    public function requireSchemaPath(): string
    {
        $this->setupPopoProperty('schemaPath');
        $this->setupDateTimeProperty('schemaPath');

        if ($this->schemaPath === null) {
            throw new UnexpectedValueException('Required value of "schemaPath" has not been set');
        }
        return $this->schemaPath;
    }

    public function setSchemaPath(?string $schemaPath): self
    {
        $this->schemaPath = $schemaPath; $this->updateMap['schemaPath'] = true; return $this;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function hasNamespace(): bool
    {
        return $this->namespace !== null;
    }

    public function requireNamespace(): string
    {
        $this->setupPopoProperty('namespace');
        $this->setupDateTimeProperty('namespace');

        if ($this->namespace === null) {
            throw new UnexpectedValueException('Required value of "namespace" has not been set');
        }
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): self
    {
        $this->namespace = $namespace; $this->updateMap['namespace'] = true; return $this;
    }

    public function getNamespaceRoot(): ?string
    {
        return $this->namespaceRoot;
    }

    public function hasNamespaceRoot(): bool
    {
        return $this->namespaceRoot !== null;
    }

    public function requireNamespaceRoot(): string
    {
        $this->setupPopoProperty('namespaceRoot');
        $this->setupDateTimeProperty('namespaceRoot');

        if ($this->namespaceRoot === null) {
            throw new UnexpectedValueException('Required value of "namespaceRoot" has not been set');
        }
        return $this->namespaceRoot;
    }

    public function setNamespaceRoot(?string $namespaceRoot): self
    {
        $this->namespaceRoot = $namespaceRoot; $this->updateMap['namespaceRoot'] = true; return $this;
    }

    public function getOutputPath(): ?string
    {
        return $this->outputPath;
    }

    public function hasOutputPath(): bool
    {
        return $this->outputPath !== null;
    }

    public function requireOutputPath(): string
    {
        $this->setupPopoProperty('outputPath');
        $this->setupDateTimeProperty('outputPath');

        if ($this->outputPath === null) {
            throw new UnexpectedValueException('Required value of "outputPath" has not been set');
        }
        return $this->outputPath;
    }

    public function setOutputPath(?string $outputPath): self
    {
        $this->outputPath = $outputPath; $this->updateMap['outputPath'] = true; return $this;
    }

    public function addPhpFilePluginClass(string $item): self
    {
        $this->phpFilePluginCollection[] = $item;

        $this->updateMap['phpFilePluginCollection'] = true;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getPhpFilePluginCollection(): array
    {
        return $this->phpFilePluginCollection;
    }

    public function hasPhpFilePluginClassCollection(): bool
    {
        return !empty($this->phpFilePluginClassCollection);
    }

    public function requirePhpFilePluginCollection(): array
    {
        $this->setupPopoProperty('phpFilePluginCollection');
        $this->setupDateTimeProperty('phpFilePluginCollection');

        if (empty($this->phpFilePluginCollection)) {
            throw new UnexpectedValueException('Required value of "phpFilePluginCollection" has not been set');
        }
        return $this->phpFilePluginCollection;
    }

    public function setPhpFilePluginCollection(array $phpFilePluginCollection): self
    {
        $this->phpFilePluginCollection = $phpFilePluginCollection; $this->updateMap['phpFilePluginCollection'] = true; return $this;
    }

    public function addNamespacePluginClass(string $item): self
    {
        $this->namespacePluginCollection[] = $item;

        $this->updateMap['namespacePluginCollection'] = true;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getNamespacePluginCollection(): array
    {
        return $this->namespacePluginCollection;
    }

    public function hasNamespacePluginClassCollection(): bool
    {
        return !empty($this->namespacePluginClassCollection);
    }

    public function requireNamespacePluginCollection(): array
    {
        $this->setupPopoProperty('namespacePluginCollection');
        $this->setupDateTimeProperty('namespacePluginCollection');

        if (empty($this->namespacePluginCollection)) {
            throw new UnexpectedValueException('Required value of "namespacePluginCollection" has not been set');
        }
        return $this->namespacePluginCollection;
    }

    public function setNamespacePluginCollection(array $namespacePluginCollection): self
    {
        $this->namespacePluginCollection = $namespacePluginCollection; $this->updateMap['namespacePluginCollection'] = true; return $this;
    }

    public function addClassPluginClass(string $item): self
    {
        $this->classPluginCollection[] = $item;

        $this->updateMap['classPluginCollection'] = true;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getClassPluginCollection(): array
    {
        return $this->classPluginCollection;
    }

    public function hasClassPluginClassCollection(): bool
    {
        return !empty($this->classPluginClassCollection);
    }

    public function requireClassPluginCollection(): array
    {
        $this->setupPopoProperty('classPluginCollection');
        $this->setupDateTimeProperty('classPluginCollection');

        if (empty($this->classPluginCollection)) {
            throw new UnexpectedValueException('Required value of "classPluginCollection" has not been set');
        }
        return $this->classPluginCollection;
    }

    public function setClassPluginCollection(array $classPluginCollection): self
    {
        $this->classPluginCollection = $classPluginCollection; $this->updateMap['classPluginCollection'] = true; return $this;
    }

    public function addPropertyPluginClass(string $item): self
    {
        $this->propertyPluginCollection[] = $item;

        $this->updateMap['propertyPluginCollection'] = true;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getPropertyPluginCollection(): array
    {
        return $this->propertyPluginCollection;
    }

    public function hasPropertyPluginClassCollection(): bool
    {
        return !empty($this->propertyPluginClassCollection);
    }

    public function requirePropertyPluginCollection(): array
    {
        $this->setupPopoProperty('propertyPluginCollection');
        $this->setupDateTimeProperty('propertyPluginCollection');

        if (empty($this->propertyPluginCollection)) {
            throw new UnexpectedValueException('Required value of "propertyPluginCollection" has not been set');
        }
        return $this->propertyPluginCollection;
    }

    public function setPropertyPluginCollection(array $propertyPluginCollection): self
    {
        $this->propertyPluginCollection = $propertyPluginCollection; $this->updateMap['propertyPluginCollection'] = true; return $this;
    }

    public function getSchemaConfigFilename(): ?string
    {
        return $this->schemaConfigFilename;
    }

    public function hasSchemaConfigFilename(): bool
    {
        return $this->schemaConfigFilename !== null;
    }

    public function requireSchemaConfigFilename(): string
    {
        $this->setupPopoProperty('schemaConfigFilename');
        $this->setupDateTimeProperty('schemaConfigFilename');

        if ($this->schemaConfigFilename === null) {
            throw new UnexpectedValueException('Required value of "schemaConfigFilename" has not been set');
        }
        return $this->schemaConfigFilename;
    }

    public function setSchemaConfigFilename(?string $schemaConfigFilename): self
    {
        $this->schemaConfigFilename = $schemaConfigFilename; $this->updateMap['schemaConfigFilename'] = true; return $this;
    }

    public function getSchemaPathFilter(): ?string
    {
        return $this->schemaPathFilter;
    }

    public function hasSchemaPathFilter(): bool
    {
        return $this->schemaPathFilter !== null;
    }

    public function requireSchemaPathFilter(): string
    {
        $this->setupPopoProperty('schemaPathFilter');
        $this->setupDateTimeProperty('schemaPathFilter');

        if ($this->schemaPathFilter === null) {
            throw new UnexpectedValueException('Required value of "schemaPathFilter" has not been set');
        }
        return $this->schemaPathFilter;
    }

    public function setSchemaPathFilter(?string $schemaPathFilter): self
    {
        $this->schemaPathFilter = $schemaPathFilter; $this->updateMap['schemaPathFilter'] = true; return $this;
    }

    public function getSchemaFilenameMask(): ?string
    {
        return $this->schemaFilenameMask;
    }

    public function hasSchemaFilenameMask(): bool
    {
        return $this->schemaFilenameMask !== null;
    }

    public function requireSchemaFilenameMask(): string
    {
        $this->setupPopoProperty('schemaFilenameMask');
        $this->setupDateTimeProperty('schemaFilenameMask');

        if ($this->schemaFilenameMask === null) {
            throw new UnexpectedValueException('Required value of "schemaFilenameMask" has not been set');
        }
        return $this->schemaFilenameMask;
    }

    public function setSchemaFilenameMask(?string $schemaFilenameMask): self
    {
        $this->schemaFilenameMask = $schemaFilenameMask; $this->updateMap['schemaFilenameMask'] = true; return $this;
    }

    public function shouldIgnoreNonExistingSchemaFolder(): ?bool
    {
        return $this->shouldIgnoreNonExistingSchemaFolder;
    }

    public function hasShouldIgnoreNonExistingSchemaFolder(): bool
    {
        return $this->shouldIgnoreNonExistingSchemaFolder !== null;
    }

    public function requireShouldIgnoreNonExistingSchemaFolder(): bool
    {
        $this->setupPopoProperty('shouldIgnoreNonExistingSchemaFolder');
        $this->setupDateTimeProperty('shouldIgnoreNonExistingSchemaFolder');

        if ($this->shouldIgnoreNonExistingSchemaFolder === null) {
            throw new UnexpectedValueException('Required value of "shouldIgnoreNonExistingSchemaFolder" has not been set');
        }
        return $this->shouldIgnoreNonExistingSchemaFolder;
    }

    public function setShouldIgnoreNonExistingSchemaFolder(bool $shouldIgnoreNonExistingSchemaFolder): self
    {
        $this->shouldIgnoreNonExistingSchemaFolder = $shouldIgnoreNonExistingSchemaFolder; $this->updateMap['shouldIgnoreNonExistingSchemaFolder'] = true; return $this;
    }
}
