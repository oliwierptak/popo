<?php

declare(strict_types = 1);

namespace Popo\Schema\Mapper;

use Popo\Plugin\MappingPolicyPluginInterface;
use ReflectionMethod;
use function get_class;

class SchemaMapper implements SchemaMapperInterface
{
    /**
     * @param array<MappingPolicyPluginInterface> $plugins
     */
    public function __construct(protected array $plugins)
    {
    }

    public function mapKeyName(array $mappings, string $key): string
    {
        foreach ($mappings as $mappingPolicy) {
            $plugin = $this->plugins[$mappingPolicy] ?? null;
            if (!$plugin) {
                continue;
            }
            $key = $plugin->run($key);
        }

        return $key;
    }

    public function generateMappingPolicyPhpCode(): array
    {
        /**
         * @var array<string, string> $result
         */
        static $result = [];
        if (!empty($result)) {
            return $result;
        }

        foreach ($this->plugins as $mappingPolicy => $plugin) {
            $method = (new ReflectionMethod(get_class($this->plugins[$mappingPolicy]), 'run'));
            $startLine = $method->getStartLine() + 1;
            $endLine = $method->getEndLine() - 1;
            $length = $endLine - $startLine;
            $source = (array) file((string) $method->getFileName());

            $body = implode('', array_slice($source, $startLine, $length));

            $result[$mappingPolicy] = $body;
        }

        return $result;
    }

    public function getSupportedMappingPolicies(): array
    {
        return array_keys($this->plugins);
    }
}
