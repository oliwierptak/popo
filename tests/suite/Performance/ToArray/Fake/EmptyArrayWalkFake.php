<?php

declare(strict_types = 1);

namespace PopoTestPerformance\ToArray\Fake;

class EmptyArrayWalkFake extends ToArrayFake
{
    public function toArray(): array
    {
        $data = [
            'idForAll' => $this->idForAll,
            'idFromExampleSchema' => $this->idFromExampleSchema,
            'fooId' => $this->fooId,
            'title' => $this->title,
            'value' => $this->value,
        ];

        array_walk(
            $data,
            function (&$value, $name){

            }
        );

        return $data;
    }
}
