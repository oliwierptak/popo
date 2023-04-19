<?php

declare(strict_types = 1);

namespace PopoTestPerformance\ToArray;

use Performance\Performance;
use PHPUnit\Framework\TestCase;
use PopoTestPerformance\ToArray\Fake\DynamicMappingFake;
use PopoTestPerformance\ToArray\Fake\EmptyArrayWalkFake;
use PopoTestPerformance\ToArray\Fake\EmptyForEachFake;
use PopoTestPerformance\ToArray\Fake\NoMappingArrayWalkFake;
use PopoTestPerformance\ToArray\Fake\NoMappingForEachFake;
use const Popo\POPO_PERFORMANCE_LOOP_MAX;

class ToArrayPerformanceTest extends TestCase
{
    /**
     * @skip
     */
    public function test_1(): void
    {
        Performance::point('empty array walk');
        for ($a = 0; $a < POPO_PERFORMANCE_LOOP_MAX; $a++) {
            (new EmptyArrayWalkFake())->toArray();
        }

        Performance::point('empty foreach');
        for ($a = 0; $a < POPO_PERFORMANCE_LOOP_MAX; $a++) {
            (new EmptyForEachFake())->toArray();
        }

        Performance::point('no mapping array walk');
        for ($a = 0; $a < POPO_PERFORMANCE_LOOP_MAX; $a++) {
            (new NoMappingArrayWalkFake())->toArray();
        }

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
