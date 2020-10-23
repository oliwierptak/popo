<?php declare(strict_types = 1);

namespace Popo;

interface PopoFacadeInterfaces
{
    public const VERSION = '3';

    /**
     * Generate POPO files according to the configurator settings.
     * There can be several sections in the .popo file, each section is represented by its own configurator.
     *
     * Specification:
     * - Check if directories set in the configurator exist, throw exception on error
     * - Generate POPO files according to $configurator settings
     * - Return updated instance of GeneratorResult
     *
     * @param Configurator $configurator
     *
     * @return GeneratorResult
     * @throws \InvalidArgumentException
     *
     * @see templates/schema.dot.json
     * @see .popo.dist
     * @see \Popo\Configurator
     */
    public function generate(Configurator $configurator): GeneratorResult;
}
