
    public function toArray(): array
    {
        $data = [];

        foreach ($this->propertyMapping as $key => $type) {
            $data[$key] = $this->default[$key] ?? null;
            $value = $this->data[$key];

            if ($this->isCollectionItem($key) && \is_array($value)) {
                foreach ($value as $popo) {
                    if (\is_object($popo) && \method_exists($popo, 'toArray')) {
                        $data[$key][] = $popo->toArray();
                    }
                }

                continue;
            }

            if (\is_object($value) && \method_exists($value, 'toArray')) {
                $data[$key] = $value->toArray();
                continue;
            }

            $data[$key] = $value;
        }

        return $data;
    }

    public function fromArray(array $data): <<RETURN_TYPE>>
    {
        $result = [];

        foreach ($this->propertyMapping as $key => $type) {
            if ($this->typeIsObject($type)) {
                $popo = new $this->propertyMapping[$key];
                if (\method_exists($popo, 'fromArray')) {
                    $popoData = $data[$key] ?? $this->default[$key] ?? [];
                    $popo->fromArray($popoData);
                }
                $result[$key] = $popo;

                continue;
            }

            if (\array_key_exists($key, $data)) {
                if ($this->isCollectionItem($key)) {
                    foreach ($data[$key] as $popoData) {
                        $popo = new $this->collectionItems[$key]();
                        if (\method_exists($popo, 'fromArray')) {
                            $popo->fromArray($popoData);
                        }
                        $result[$key][] = $popo;
                    }
                } else {
                    $result[$key] = $data[$key];
                }
            } else {
                $result[$key] = $this->default[$key] ?? null;
            }
        }

        $this->data = $result;

        foreach ($data as $key => $value) {
            if (!\array_key_exists($key, $result)) {
                continue;
            }

            $type = $this->propertyMapping[$key] ?? 'string';
            $value = $this->typecastValue($type, $result[$key]);
            $this->popoSetValue($key, $value);
        }

        return $this;
    }

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
        return \array_key_exists($key, $this->collectionItems);
    }

    protected function typeIsObject(string $value): bool
    {
    return $value[0] === '\\' && \ctype_upper($value[1]);
    }
