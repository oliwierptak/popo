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
    * @return <<RETURN_TYPE>>
    */
    public function fromArray(array $data): <<RETURN_TYPE>>;

    <<METHODS>>

    <<COLLECTION>>
}
