<?php declare(strict_types = 1);

namespace Popo;

interface PopoFacadeInterfaces
{
    public const VERSION = '3';

    /**
     * Specification:
     * - Check if directories set in the configurator exist, throw exception on error
     * - Generate POPO files according to the configurator settings.
     * - Return updated instance of GeneratorResult
     *
     * @param Configurator $configurator
     *
     * @return GeneratorResult
     * @throws \InvalidArgumentException
     *
     * @see templates/schema/schema.dot.json
     * @see .popo.dist
     * @see \Popo\Configurator
     */
    public function generate(Configurator $configurator): GeneratorResult;
}
