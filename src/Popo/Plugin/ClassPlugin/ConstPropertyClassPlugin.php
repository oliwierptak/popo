<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;
use Popo\Plugin\MappingPolicy\CamelToSnakeMappingPolicyPlugin;
use Popo\Plugin\MappingPolicy\UpperMappingPolicyPlugin;

class ConstPropertyClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        foreach ($builder->getSchema()->getPropertyCollection() as $metadataProperty) {
            $builder->getClass()
                ->addConstant(
                    $this->generateConstantName($metadataProperty->getName()),
                    $metadataProperty->getName(),
                )
                ->setPublic();
        }
    }

    protected function generateConstantName(string $name): string
    {
        static $plugins;

        if (empty($plugins)) {
            $plugins[] = new CamelToSnakeMappingPolicyPlugin();
            $plugins[] = new UpperMappingPolicyPlugin();
        }

        foreach ($plugins as $plugin) {
            $name = $plugin->run($name);
        }

        return $name;
    }
}
