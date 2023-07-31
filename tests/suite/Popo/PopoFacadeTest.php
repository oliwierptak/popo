<?php

declare(strict_types = 1);

namespace PopoTestSuite;

use PHPUnit\Framework\TestCase;
use Popo\Plugin\ClassPluginInterface;
use Popo\Plugin\MappingPolicyPluginInterface;
use Popo\Plugin\NamespacePluginInterface;
use Popo\Plugin\PhpFilePluginInterface;
use Popo\Plugin\PropertyPluginInterface;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use Popo\PopoFactory;
use Popo\Schema\Validator\Exception\SchemaValidationException;
use PopoTestSuiteHelper\SetupTrait;
use ReflectionClass;
use ReflectionProperty;
use function get_class;
use const Popo\POPO_TESTS_DIR;

/**
 * @group functional
 */
class PopoFacadeTest extends TestCase
{
    use SetupTrait;

    public function test_generate_bundles(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/')
            ->setOutputPath(POPO_TESTS_DIR)
            ->setSchemaPathFilter('bundles')
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/bundles/project.config.yml');

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Shared/Foo.php');
    }

    public function test_generate_from_path_popo(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Foo.php');
    }

    public function test_generate_from_path_popo_and_config(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR)
            ->setSchemaConfigFilename(POPO_TESTS_DIR . 'fixtures/popo.config.yml');

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Foo.php');
    }

    public function test_generate_from_popo_file(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Buzz/Buzz.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/AnotherFoo.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Popo/Foo.php');
    }

    public function test_generate_readme_example(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-readme.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Readme/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Readme/Foo.php');
    }

    public function test_generate_example_with_namespace_root(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-namespace-root.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'AppWithNamespaceRoot/Example/Bar.php');
        $this->assertFileExists(POPO_TESTS_DIR . 'AppWithNamespaceRoot/Example/Foo.php');
    }

    public function test_inheritance(): void
    {
        $facade = new PopoFacade();

        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-inheritance.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $facade->generate($configurator);

        $this->assertFileExists(POPO_TESTS_DIR . 'App/Example/Inheritance/FooBar.php');
    }

    public function test_set_factory(): void
    {
        $facade = new PopoFacade();
        $facade->setFactory(new PopoFactory());

        $property = $this->getProtectedProperty(PopoFacade::class, 'factory');

        $this->assertInstanceOf(PopoFactory::class, $property->getValue($facade));
    }

    public function test_reconfigure(): void
    {
        $configurator = (new PopoConfigurator())
            ->setSchemaPath(POPO_TESTS_DIR . 'fixtures/popo-readme.yml')
            ->setOutputPath(POPO_TESTS_DIR);

        $classPluginClassName = get_class($this->createMock(ClassPluginInterface::class));
        $mappingPolicyPluginClassName = get_class($this->createMock(MappingPolicyPluginInterface::class));
        $namespacePluginClassName = get_class($this->createMock(NamespacePluginInterface::class));
        $phpFilePluginClassName = get_class($this->createMock(PhpFilePluginInterface::class));
        $propertyPluginNock = get_class($this->createMock(PropertyPluginInterface::class));

        (new PopoFacade())
            ->addClassPluginClassName($classPluginClassName)
            ->addMappingPolicyPluginClassName($mappingPolicyPluginClassName)
            ->addNamespacePluginClassName($namespacePluginClassName)
            ->addPhpFilePluginClassName($phpFilePluginClassName)
            ->addPropertyPluginClassName($propertyPluginNock);

        $configurator = (new PopoFacade())
            ->reconfigure($configurator);

        $this->assertContains(
            $classPluginClassName,
            $configurator->getClassPluginCollection(),
        );
        $this->assertContains(
            $mappingPolicyPluginClassName,
            $configurator->getMappingPolicyPluginCollection(),
        );
        $this->assertContains(
            $namespacePluginClassName,
            $configurator->getNamespacePluginCollection(),
        );
        $this->assertContains(
            $phpFilePluginClassName,
            $configurator->getPhpFilePluginCollection(),
        );
        $this->assertContains(
            $propertyPluginNock,
            $configurator->getPropertyPluginCollection(),
        );
    }

    public function test_validate_required(): void
    {
        $this->expectException(SchemaValidationException::class);
        $this->expectExceptionMessage('The child config "namespace" under "config" must be configured.');

        $facade = new PopoFacade();

        $facade->validate([
            'config' => [
                'attribute' => 'a'
            ]
        ]);
    }

    private function getProtectedProperty($class, $name): ReflectionProperty
    {
        $Reflection = new ReflectionClass($class);
        $property = $Reflection->getProperty($name);
        $property->setAccessible(true);

        return $property;
    }
}
