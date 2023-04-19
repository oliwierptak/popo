<?php

declare(strict_types = 1);

namespace PopoTestPerformance\ToArray;

use Performance\Performance;
use PHPUnit\Framework\TestCase;
use PopoTestPerformance\ToArray\Fake\DynamicMappingFake;
use PopoTestPerformance\ToArray\Fake\NoMappingForEachFake;
use const Popo\POPO_PERFORMANCE_LOOP_MAX;

class BasicTest extends TestCase
{
    public function test_1(): void
    {
        Performance::point('no mapping for each');
        for ($a = 0; $a < POPO_PERFORMANCE_LOOP_MAX; $a++) {
            (new NoMappingForEachFake())->toArray();
        }

        Performance::point('dynamic mapping');
        for ($a = 0; $a < POPO_PERFORMANCE_LOOP_MAX; $a++) {
            (new DynamicMappingFake())->toArray();
        }

        Performance::results();

        $this->assertTrue(true);
    }
}
