<?php declare(strict_types = 1);

namespace <<NAMESPACE>>;

use InvalidArgumentException;
use UnexpectedValueException;
use function array_key_exists;
use function ctype_upper;
use function is_array;
use function is_object;
use function method_exists;
use function sprintf;
use function strtolower;
use function trim;

/**
 * Code generated by POPO generator, do not edit.
 * https://packagist.org/packages/popo/generator
 */
class <<CLASSNAME>><<EXTENDS>> <<IMPLEMENTS_INTERFACE>>
{
    protected array $data = <<SCHEMA_DATA>>;

    protected array $default = <<SCHEMA_DATA>>;

    protected array $propertyMapping = <<PROPERTY_MAPPING>>;

    protected array $collectionItems = <<COLLECTION_ITEMS>>;

    protected array $updateMap = [];

    /**
     * @param string $property
     *
     * @return mixed|null
     */
    protected function popoGetValue(string $property)
    {
        if (!isset($this->data[$property])) {
            if ($this->typeIsObject($this->propertyMapping[$property])) {
                $popo = new $this->propertyMapping[$property];
                $this->data[$property] = $popo;
            } else {
                return null;
            }
        }

        return $this->data[$property];
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return void
     */
    protected function popoSetValue(string $property, $value): void
    {
        $this->data[$property] = $value;

        $this->updateMap[$property] = true;
    }

    /**
     * @param string $property
     *
     * @throws UnexpectedValueException
     * @return void
     */
    protected function assertPropertyValue(string $property): void
    {
        if (!isset($this->data[$property])) {
            throw new UnexpectedValueException(sprintf(
                'Required value of "%s" has not been set',
                $property
            ));
        }
    }

    /**
     * @param string $propertyName
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function addCollectionItem(string $propertyName, $value): void
    {
        $type = trim(strtolower($this->propertyMapping[$propertyName]));
        $collection = $this->popoGetValue($propertyName) ?? [];

        if (!is_array($collection) || $type !== 'array') {
            throw new InvalidArgumentException('Cannot add item to non array type: ' . $propertyName);
        }

        $collection[] = $value;

        $this->popoSetValue($propertyName, $collection);
    }

    public function toArray(): array
    {
        $data = [];

        foreach ($this->propertyMapping as $key => $type) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = $this->default[$key] ?? null;
            }
            $value = $this->data[$key];

            if ($this->isCollectionItem($key) && is_array($value)) {
                foreach ($value as $popo) {
                    if (is_object($popo) && method_exists($popo, 'toArray')) {
                        $data[$key][] = $popo->toArray();
                    }
                }

                continue;
            }

            if (is_object($value) && method_exists($value, 'toArray')) {
                $data[$key] = $value->toArray();
                continue;
            }

            $data[$key] = $value;
        }

        return $data;
    }

    public function fromArray(array $data): <<RETURN_TYPE>>
    {
        foreach ($this->propertyMapping as $key => $type) {
            $result[$key] = $this->default[$key] ?? null;

            if ($this->typeIsObject($type)) {
                $popo = new $this->propertyMapping[$key];
                if (method_exists($popo, 'fromArray')) {
                    $popoData = $data[$key] ?? $this->default[$key] ?? [];
                    $popo->fromArray($popoData);
                }
                $result[$key] = $popo;

                continue;
            }

            if (array_key_exists($key, $data)) {
                if ($this->isCollectionItem($key)) {
                    foreach ($data[$key] as $popoData) {
                        $popo = new $this->collectionItems[$key]();
                        if (method_exists($popo, 'fromArray')) {
                            $popo->fromArray($popoData);
                        }
                        $result[$key][] = $popo;
                    }
                } else {
                    $result[$key] = $data[$key];
                }
            }
        }

        $this->data = $result;

        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $result)) {
                continue;
            }

            $type = $this->propertyMapping[$key] ?? null;
            if ($type !== null) {
                $value = $this->typecastValue($type, $result[$key]);
                $this->popoSetValue($key, $value);
            }
        }

        return $this;
    }

    /**
     * @param string $type
     * @param mixed $value
     *
     * @return mixed
     */
    protected function typecastValue(string $type, $value)
    {
        if ($value === null) {
            return $value;
        }

        switch ($type) {
            case 'int':
                $value = (int)$value;
                break;
            case 'string':
                $value = (string)$value;
                break;
            case 'bool':
                $value = (bool)$value;
                break;
            case 'array':
                $value = (array)$value;
                break;
        }

        return $value;
    }

    protected function isCollectionItem(string $key): bool
    {
        return array_key_exists($key, $this->collectionItems);
    }

    protected function typeIsObject(string $value): bool
    {
        return $value[0] === '\\' && ctype_upper($value[1]);
    }

    public function isNew(): bool
    {
        return empty($this->updateMap);
    }
    <<METHODS>>
    <<COLLECTION>>
}
