<?php declare(strict_types = 1);

namespace Popo\Schema\Validator;

use Popo\Schema\Validator\Exception\SchemaValidationException;
use Symfony\Component\Config\Definition\ConfigurableInterface;
use Symfony\Component\Config\Definition\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Throwable;

class Validator
{
    /**
     * @param array<ConfigurableInterface> $plugins
     */
    public function __construct(private array $plugins) {}

    /**
     * @param array<string, mixed> $schemaData
     *
     * @retun array<string, mixed>
     *
     * @throws \Popo\Schema\Validator\Exception\SchemaValidationException
     */
    public function validate(array $schemaData): array
    {
        $processor = new Processor();

        $result = [];
        foreach ($schemaData as $sectionName => $config) {
            foreach ($this->plugins as $pluginName => $plugin) {
                if (strcasecmp($pluginName, $sectionName) !== 0) {
                    continue;
                }

                $configuration = new Configuration(
                    $plugin,
                    new ContainerBuilder(new ParameterBag()),
                    $pluginName
                );

                try {
                    $result[$sectionName] = $processor->processConfiguration($configuration, [$config]);
                }
                catch (Throwable $e) {
                    throw new SchemaValidationException($e->getMessage(), $e->getCode(), $e);
                }
            }
        }

        return $result;
    }
}