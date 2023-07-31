<?php declare(strict_types = 1);

namespace Popo\Schema\Validator;

use Symfony\Component\Config\Definition\ConfigurableInterface;
use Symfony\Component\Config\Definition\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class Validator
{
    /**
     * @param array<ConfigurableInterface> $plugins
     */
    public function __construct(private array $plugins) {}

    public function validate(array $schemaData): array
    {
        $result = [];
        foreach ($schemaData as $sectionName => $config) {
            foreach ($this->plugins as $plugin) {
                $configuration = new Configuration(
                    $plugin,
                    new ContainerBuilder(new ParameterBag()),
                    $plugin::ALIAS
                );

                if ($plugin::ALIAS !== $sectionName) {
                    continue;
                }

                $result[$sectionName] = (new Processor())->processConfiguration($configuration, [$config]);
            }
        }

        return $result;
    }
}