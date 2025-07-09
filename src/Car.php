<?php

namespace Max\HexletSlimExample;

class Car
{
    private ?string $id = null;
    public function __construct(
        private ?string $make = null,
        private ?string $model = null
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self($data[0], $data[1]);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function exists(): bool
    {
        return !is_null($this->id);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMake(): ?string
    {
            return $this->make;
    }

    public function getModel(): ?string
    {
            return $this->model;
    }
}