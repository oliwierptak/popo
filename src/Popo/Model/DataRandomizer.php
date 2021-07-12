<?php

declare(strict_types = 1);

namespace Popo\Model;

class DataRandomizer
{
    public function randomize(string $type, array $properties = []): mixed
    {
        $data['bool'] = [
            true,
            false,
            false,
        ];

        $data['float'] = [
            1.07,
            123.99,
            9988.55,
        ];

        $data['int'] = [
            1,
            123,
            9988,
        ];

        $data['string'] = [
            'Lorem ipsum',
            'Lorem ipsum foo bar',
            'Lorem ipsum foo bar buzz',
        ];

        $data['popo'] = [
            null,
            null,
            null,
        ];

        $data['mixed'] = [
            1,
            'abc',
            99.88,
        ];

        $data['const'] = [
            null,
            null,
            null,
        ];

        $data['array'] = [];

        for ($a=0; $a<=2; $a++) {
            $list = [];
            foreach ($properties as $index => $property) {
                if ($property->getType() === 'popo' || $property->getType() === 'const' || $property->getType() === 'array') {
                    continue;
                }
                $list[$property->getName()] = $data[$property->getType()][rand(0, 2)];
            }

            $data['array'][] = $list;
        }

        return $data[$type][rand(0,2)];
    }
}
