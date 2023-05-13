<?php

declare(strict_types = 1);

namespace AppTestSuite;

use App\Example\MappingPolicy\Blog;
use App\Example\MappingPolicy\DocumentData;
use PHPUnit\Framework\TestCase;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoTestSuiteHelper\RemoveGeneratedClassesTrait;
use const Popo\POPO_TESTS_DIR;

/**
 * @group unit
 */
class PopoMappingPolicyTest extends TestCase
{
    use RemoveGeneratedClassesTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-mapping-policy.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);
    }

    public function test_toArray(): void
    {
        $blog = (new Blog());

        $this->assertEquals(
            [
                'BLOG_TITLE' => null,
                'blog_data' => [
                    'someValue' => null,
                ],
                'commentThread' => ['one','two','three'],
            ],
            $blog->toArray()
        );
    }

    public function test_fromArray(): void
    {
        $blog = (new Blog())
            ->fromArray([
                'BLOG_TITLE' => 'Lorem Ipsum',
                'blog_data' => [
                    'someValue' => 'some value data',
                ],
                'commentThread' => ['four','five'],
            ]);

        $this->assertEquals(
            [
                'BLOG_TITLE' => 'Lorem Ipsum',
                'blog_data' => [
                    'someValue' => 'some value data',
                ],
                'commentThread' => ['four','five'],
            ],
            $blog->toArray()
        );
    }

    public function test_fromArray_should_skip_invalid_keys(): void
    {
        $blog = (new Blog())
            ->fromArray([
                'blog_title' => 'A value',
                'blogData' => [
                    'someValue' => 'LoremIpsumBarData',
                ]
            ]);

        $this->assertEquals(
            [
                'BLOG_TITLE' => null,
                'blog_data' => [
                    'someValue' => null,
                ],
                'commentThread' => ['one','two','three'],
            ],
            $blog->toArray()
        );
    }

    public function test_toArraySnakeToCamel(): void
    {
        $documentData = (new DocumentData())
            ->fromArray([
                'some_title' => 'a title',
                'SOME_VALUE' => 111,
            ]);

        $this->assertEquals([
            'someTitle' => 'a title',
            'someValue' => 111,
        ],
            $documentData->toArraySnakeToCamel()
        );
    }
}
