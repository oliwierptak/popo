<?php

declare(strict_types = 1);

namespace Generated\Popo;

interface FooBarInterface
{
    /**
    * @return array
    */
    public function toArray(): array;

    /**
    * @param array $data
    *
    * @return \Generated\Popo\FooBarInterface
    */
    public function fromArray(array $data): \Generated\Popo\FooBarInterface;

    
    /**
     * @return integer|null
     */
    public function getFooBarId(): ?int;

    /**
     * @param integer|null $fooBarId
     *
     * @return self
     */
    public function setFooBarId(?int $fooBarId): \Generated\Popo\FooBarInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return integer
     */
    public function requireFooBarId(): int;

    /**
     * @return string|null
     */
    public function getFooBarStringValue(): ?string;

    /**
     * @param string|null $fooBarStringValue
     *
     * @return self
     */
    public function setFooBarStringValue(?string $fooBarStringValue): \Generated\Popo\FooBarInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requireFooBarStringValue(): string;

    /**
     * @return string|null
     */
    public function getBuzzPropertyInFooBar(): ?string;

    /**
     * @param string|null $buzzPropertyInFooBar
     *
     * @return self
     */
    public function setBuzzPropertyInFooBar(?string $buzzPropertyInFooBar): \Generated\Popo\FooBarInterface;

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function requireBuzzPropertyInFooBar(): string;

}
