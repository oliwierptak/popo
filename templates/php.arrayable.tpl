
    public function toArray(): array
    {
        $data = $this->prepareToArrayData();

        foreach ($this->propertyMapping as $key => $type) {
            $data[$key] = $this->default[$key] ?? null;

            if (isset($this->data[$key])) {
                $value = $this->data[$key];

                if ($this->collectionItems[$key] !== '') {
                    if (\is_array($value) && \class_exists($this->collectionItems[$key])) {
                        foreach ($value as $popo) {
                            if (\method_exists($popo, 'toArray')) {
                                $data[$key][] = $popo->toArray();
                            }
                        }
                    }
                } else {
                    $data[$key] = $value;
                }

                if (\is_object($value) && \method_exists($value, 'toArray')) {
                    $data[$key] = $value->toArray();
                }
            }
        }

        return $data;
    }

    public function fromArray(array $data): <<RETURN_TYPE>>
    {
        $result = $this->prepareFromArrayData($data);

        foreach ($this->propertyMapping as $key => $type) {
            $result[$key] = null;
            if (\array_key_exists($key, $this->default)) {
                $result[$key] = $this->default[$key];
            }
            if (\array_key_exists($key, $data)) {
                if ($this->isCollectionItem($key, $data)) {
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
            }

            if (\class_exists($type)) {
                $popo = new $type();
                if (\is_array($result[$key]) && \method_exists($popo, 'fromArray')) {
                    $popo->fromArray($result[$key]);
                }
                $result[$key] = $popo;
            }
        }

        $this->data = $result;

        foreach ($data as $key => $value) {
            $value = $result[$key];
            $this->popoSetValue($key, $value);
        }

        return $this;
    }

    protected function prepareToArrayData(): array
    {
        $data = [];
        $parents = \class_parents($this, false);
        if (count($parents) === 1 && \current($parents) === \get_class($this)) {
            $data = [];
        } else if (count($parents) > 1) {
            $parent = \get_parent_class($this);
            if (method_exists($parent, 'toArray')) {
                $data = parent::toArray();
            }
        }
        return $data;
    }

    protected function prepareFromArrayData(array $data): array
    {
        $parents = \class_parents($this, false);
        if (count($parents) === 1 && \current($parents) === \get_class($this)) {
            $data = [];
        } else if (count($parents) > 1) {
            $parent = \get_parent_class($this);
            if (method_exists($parent, 'fromArray')) {
                $data = parent::fromArray();
            }
        }
        return $data;
    }
