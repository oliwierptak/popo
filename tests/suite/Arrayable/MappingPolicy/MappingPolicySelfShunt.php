<?php

declare(strict_types = 1);

namespace PopoTestArrayable\MappingPolicy;

use App\Example\Popo\Bar;
use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use function array_key_exists;
use function strpos;

class MappingPolicySelfShunt extends TestCase
{
    protected const METADATA = [
        'idForAll' => [
            'type' => 'int',
            'default' => 30,
            'mappingPolicy' => ['none'],
            'mappingPolicyValue' => 'idForAll',
        ],
        'idFromExampleSchema' => [
            'type' => 'int',
            'default' => 20,
            'mappingPolicy' => ['none'],
            'mappingPolicyValue' => 'idFromExampleSchema',
        ],
        'fooId' => [
            'type' => 'int',
            'default' => null,
            'mappingPolicy' => ['camel-to-snake', 'upper'],
            'mappingPolicyValue' => 'FOO_ID',
        ],
        'title' => [
            'type' => 'string',
            'default' => 'Example Foo Hakuna Matata',
            'mappingPolicy' => ['none'],
            'mappingPolicyValue' => 'title',
        ],
        'value' => [
            'type' => 'int',
            'default' => \App\ExampleInterface::TEST_BUZZ,
            'mappingPolicy' => ['none'],
            'mappingPolicyValue' => 'value',
        ],
        'bar' => [
            'type' => 'popo',
            'default' => \App\Example\Popo\Bar::class,
            'mappingPolicy' => 0,
            'mappingPolicyValue' => 'bar',
        ],
    ];

    protected array $updateMap = [];
    protected ?int $idForAll = 30;
    protected ?int $idFromExampleSchema = 20;
    protected ?int $fooId = null;
    protected ?string $title = 'Example Foo Hakuna Matata';
    protected ?int $value = \App\ExampleInterface::TEST_BUZZ;
    protected ?Bar $bar = null;

    public function fromArray(array $data): self
    {
        static $metadata = [
            'idForAll' => 'idForAll',
            'idFromExampleSchema' => 'idFromExampleSchema',
            'fooId' => 'FOO_ID',
            'title' => 'title',
            'value' => 'value',
            'bar' => 'bar',
        ];

        foreach ($metadata as $name => $mappedName) {
            $meta = static::METADATA[$name];
            $value = $data[$mappedName] ?? $this->$name ?? null;
            $popoValue = $meta['default'];

            if ($popoValue !== null && $meta['type'] === 'popo') {
                $popo = new $popoValue;

                if (is_array($value)) {
                    $popo->fromArray($value);
                }

                $value = $popo;
            }

            if ($meta['type'] === 'datetime') {
                if (($value instanceof DateTime) === false) {
                    $datetime = new DateTime($data[$name] ?? $meta['default']);
                    $timezone = $meta['timezone'] ?? null;
                    if ($timezone !== null) {
                        $timezone = new DateTimeZone($timezone);
                        $datetime = new DateTime($data[$name] ?? static::METADATA[$name]['default'], $timezone);
                    }
                    $value = $datetime;
                }
            }

            $this->$name = $value;
            if (array_key_exists($mappedName, $data)) {
                $this->updateMap[$name] = true;
            }
        }

        return $this;
    }

    protected function toArray(): array
    {
        static $metadata = [
            'idForAll' => 'idForAll',
            'idFromExampleSchema' => 'idFromExampleSchema',
            'fooId' => 'FOO_ID',
            'title' => 'title',
            'value' => 'value',
            'bar' => 'bar',
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

    public function toMappedArray(...$mappings): array
    {
        return $this->map($this->toArray(), $mappings);
    }

    public function fromMappedArray(array $data, ...$mappings): self
    {
        $result = [];
        foreach (static::METADATA as $name => $propertyMetadata) {
            $mappingPolicyValue = $propertyMetadata['mappingPolicyValue'];
            $inputKey = $this->mapKeyName($mappings, $mappingPolicyValue);
            $value = $data[$inputKey] ?? null;

            if (static::METADATA[$name]['type'] === 'popo') {
                $popo = static::METADATA[$name]['default'];
                $value = $this->$name !== null
                    ? $this->$name->fromMappedArray($value ?? [], ...$mappings)
                    : (new $popo)->fromMappedArray($value ?? [], ...$mappings);
                $value = $value->toArray();
            }

            $result[$mappingPolicyValue] = $value;
        }

        $this->fromArray($result);

        return $this;
    }

    protected function map(array $data, array $mappings): array
    {
        $result = [];
        foreach (static::METADATA as $name => $propertyMetadata) {
            $value = $data[$propertyMetadata['mappingPolicyValue']];

            if (static::METADATA[$name]['type'] === 'popo') {
                $popo = static::METADATA[$name]['default'];
                $value = $this->$name !== null ? $this->$name->toMappedArray(...$mappings) : (new $popo)->toMappedArray(
                    ...$mappings
                );
            }

            $key = $this->mapKeyName($mappings, $propertyMetadata['mappingPolicyValue']);
            $result[$key] = $value;
        }

        return $result;
    }

    protected function mapKeyName(array $mappings, string $key): string
    {
        static $mappingPolicy = [];

        if (empty($mappingPolicy)) {
            $mappingPolicy['none'] =
                // 0 - nothing
                static function (string $key): string {
                    return $key;
                };
            $mappingPolicy['lower'] =
                // 1 - to lower
                static function (string $key): string {
                    return mb_strtolower($key);
                };
            $mappingPolicy['upper'] =
                // 2 - to upper
                static function (string $key): string {
                    return mb_strtoupper($key);
                };
            $mappingPolicy['camel-to-snake'] =
                // 3 - CamelToUnderscore
                static function (string $key): string {
                    $pos = strpos($key, '_');
                    if ($pos !== false) {
                        return $key;
                    }

                    $camelizedStringTokens = preg_split('/(?<=[^A-Z])(?=[A-Z])/', $key);
                    if (count($camelizedStringTokens) > 0) {
                        return mb_strtolower(implode('_', $camelizedStringTokens));
                    }

                    return $key;
                };
            $mappingPolicy['snake-to-camel'] =
                // 4 - UnderscoreToCamel
                static function (string $key): string {
                    $stringTokens = explode('_', mb_strtolower($key));
                    $camelizedString = array_shift($stringTokens);
                    foreach ($stringTokens as $token) {
                        $camelizedString .= ucfirst($token);
                    }

                    return $camelizedString;
                };
            //@TODO add support for id-from-example-schema
        }

        foreach ($mappings as $mappingIndex => $mappingType) {
            if (!array_key_exists($mappingType, $mappingPolicy)) {
                continue;
            }

            $key = $mappingPolicy[$mappingType]($key);
        }

        return $key;
    }
}
