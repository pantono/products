<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Categories;
use Pantono\Contracts\Attributes\DatabaseTable;

#[Locator(methodName: 'getStatusById', className: Categories::class), DatabaseTable(table: 'category_status', idColumn: 'id')]
class CategoryStatus
{
    private ?int $id = null;
    private string $name;
    private bool $visible;

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

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }
}
