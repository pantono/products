<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;
use Pantono\Contracts\Attributes\DatabaseTable;

#[Locator(methodName: 'getFlagById', className: Products::class), DatabaseTable(table: 'flag', idColumn: 'id')]
class Flag
{
    private ?int $id = null;
    private string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
