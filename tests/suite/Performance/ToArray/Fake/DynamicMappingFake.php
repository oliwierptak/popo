<?php

declare(strict_types = 1);

namespace PopoTestPerformance\ToArray\Fake;

use DateTime;
use DateTimeZone;

class DynamicMappingFake extends ToArrayFake
{
    public function toArray(): array
    {
        $metadata = [
            'idForAll' => 'idForAll',
            'idFromExampleSchema' => 'idFromExampleSchema',
            'fooId' => 'FOO_ID',
            'title' => 'title',
            'value' => 'value',
        ];

        $data = [];
        foreach ($metadata as $name => $mappedName) {
            $value = $this->$name;

            if (static::METADATA[$name]['type'] === 'popo') {
                $popo = static::METADATA[$name]['default'];
                $value = $this->$name !== null ? $this->$name->toArray() : (new $popo)->toArray();
            }

            if (static::METADATA[$name]['type'] === 'datetime') {
                if (($value instanceof DateTime) === false) {
                    $datetime = new DateTime(static::METADATA[$name]['default']);
                    $timezone = static::METADATA[$name]['timezone'] ?? null;
                    if ($timezone !== null) {
                        $timezone = new DateTimeZone($timezone);
                        $datetime = new DateTime($this->$name ?? static::METADATA[$name]['default'], $timezone);
                    }
                    $value = $datetime;
                }

                $value = $value->format(static::METADATA[$name]['format']);
            }

            $data[$mappedName] = $value;
        }

        return $data;
    }
}
