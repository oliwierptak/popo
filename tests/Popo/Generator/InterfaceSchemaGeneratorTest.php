<?php

declare(strict_types = 1);

namespace Tests\Popo\Generator;

use Popo\Builder\BuilderConfigurator;
use Popo\Schema\SchemaConfigurator;
use function trim;

class InterfaceSchemaGeneratorTest extends SchemaGeneratorTest
{
    public function testGenerateInterfaces(): void
    {
        $schemaBuilderConfigurator = (new SchemaConfigurator())
            ->setSchemaTemplateFilename('interface/php.interface.schema.tpl')
            ->setPropertyTemplateFilename('interface/php.interface.property.tpl')
            ->setCollectionTemplateFilename('interface/php.interface.collection.tpl');

        $configurator = (new BuilderConfigurator())
            ->setSchemaConfigurator($schemaBuilderConfigurator)
            ->setTemplateDirectory($this->templateDirectory)
            ->setSchemaDirectory($this->schemaDirectory);

        $generator = $this->buildGenerator($configurator);
        $schema = $this->buildSchema();

        $schemaString = $generator->generate($schema);

        $expectedString = '
<?php

declare(strict_types = 1);

namespace Popo\Tests;

interface FooStubInterface
{
    /**
    * @return array
    */
    public function toArray(): array;

    /**
    * @param array $data
    *
    * @return \Popo\Tests\FooStubInterface
    */
    public function fromArray(array $data): \Popo\Tests\FooStubInterface;

    
    /**
     * @return integer|null Lorem Ipsum
     */
    public function getId(): ?int;

    /**
     * @param integer|null $id Lorem Ipsum
     *
     * @return self Lorem Ipsum
     */
    public function setId(?int $id): \Popo\Tests\FooStubInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return integer Lorem Ipsum
     */
    public function requireId(): int;

    /**
     * @return string|null
     */
    public function getUsername(): ?string;

    /**
     * @param string|null $username
     *
     * @return self
     */
    public function setUsername(?string $username): \Popo\Tests\FooStubInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requireUsername(): string;

    /**
     * @return string|null
     */
    public function getPassword(): ?string;

    /**
     * @param string|null $password
     *
     * @return self
     */
    public function setPassword(?string $password): \Popo\Tests\FooStubInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requirePassword(): string;

    /**
     * @return array|null
     */
    public function getOptionalData(): ?array;

    /**
     * @param array|null $optionalData
     *
     * @return self
     */
    public function setOptionalData(?array $optionalData): \Popo\Tests\FooStubInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return array
     */
    public function requireOptionalData(): array;

    /**
     * @return \Popo\Tests\BarStubInterface|null
     */
    public function getBar(): ?\Popo\Tests\BarStubInterface;

    /**
     * @param \Popo\Tests\BarStubInterface|null $bar
     *
     * @return self
     */
    public function setBar(?\Popo\Tests\BarStubInterface $bar): \Popo\Tests\FooStubInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return \Popo\Tests\BarStubInterface
     */
    public function requireBar(): \Popo\Tests\BarStubInterface;


    
    /**
     * @param string $item
     *
     * @return self
     */
    public function addOptionalDataItem(string $item): \Popo\Tests\FooStubInterface;

}
        ';

        $this->assertEquals(trim($expectedString), trim($schemaString));
    }
}
