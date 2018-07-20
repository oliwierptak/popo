<?php

declare(strict_types = 1);

namespace Tests\Popo\Schema;

use PHPUnit\Framework\TestCase;
use Popo\Schema\Reader\Schema;

class SchemaTest extends TestCase
{
    /**
     * @var array
     */
    protected $schema;

    protected function setUp(): void
    {
        $this->schema = [
            'name' => 'Tests\App\Generated\Popo\PopoStub',
            'schema' => [[
                'name' => 'id',
                'type' => 'int',
                'docblock' => 'Lorem Ipsum',
            ],[
                'name' => 'username',
                'type' => 'string',
            ],[
                'name' => 'password',
                'type' => 'string',
            ],[
                'name' => 'isLoggedIn',
                'type' => 'bool',
                'default' => false,
            ]],
        ];
    }

    public function testSchema(): void
    {
        $schema = new Schema($this->schema);

        $this->assertSame($this->schema[Schema::NAME], $schema->getName());
        $this->assertSame($this->schema[Schema::SCHEMA], $schema->getSchema());
    }
}
