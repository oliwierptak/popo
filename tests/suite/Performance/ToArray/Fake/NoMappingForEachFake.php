<?php

declare(strict_types = 1);

namespace PopoTestPerformance\ToArray\Fake;

use DateTime;
use DateTimeZone;

class NoMappingForEachFake extends ToArrayFake
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

        foreach ($data as $name => &$value) {
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
        }

        return $data;
    }
}
