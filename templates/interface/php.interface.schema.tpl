<?php

declare(strict_types = 1);

namespace <<NAMESPACE>>;

interface <<CLASSNAME>>Interface
{
    /**
    * @return array
    */
    public function toArray(): array;

    /**
    * @param array $data
    *
    * @return \<<NAMESPACE>>\<<CLASSNAME>>Interface
    */
    public function fromArray(array $data): \<<NAMESPACE>>\<<CLASSNAME>>Interface;

    <<METHODS>>
}
