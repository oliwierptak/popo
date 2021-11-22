<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\Literal;

class PopoBuilder8 extends PopoBuilder
{
    protected function addToArrayMethod(): self
    {
        parent::addToArrayMethod();

        $this->class->getMethod('toArray')
            ->addAttribute(
                'JetBrains\PhpStorm\ArrayShape',
                [new Literal('self::SHAPE_PROPERTIES')]
            );

        return $this;
    }

    protected function addFromArrayMethod(): self
    {
        parent::addFromArrayMethod();

        $this->class->getMethod('fromArray')
            ->addAttribute(
                'JetBrains\PhpStorm\ArrayShape',
                [new Literal('self::SHAPE_PROPERTIES')]
            );

        return $this;
    }

    protected function addMetadataShapeConstant(): self
    {
        parent::addMetadataShapeConstant();

        $this->class
            ->addConstant(
                'SHAPE_PROPERTIES',
                $this->generateShapeProperties()
            )
            ->setProtected();

        return $this;
    }
}
