<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Filter;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Categories;
use Pantono\Contracts\Attributes\DatabaseTable;

#[Locator(methodName: 'getFieldTypeById', className: Categories::class), DatabaseTable(table: 'category_field_type', idColumn: 'id')]
class CategoryFieldType
{
    use SavableModel;

    private ?int $id = null;
    private string $name;
    private string $label;
    private string $type;
    private ?string $regex = null;
    /**
     * @var array<string>
     */
    #[Filter('json_decode')]
    private array $allowedValues = [];
    private ?string $allowedValuesQuery = null;

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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getRegex(): ?string
    {
        return $this->regex;
    }

    public function setRegex(?string $regex): void
    {
        $this->regex = $regex;
    }

    public function getAllowedValues(): array
    {
        return $this->allowedValues;
    }

    public function setAllowedValues(array $allowedValues): void
    {
        $this->allowedValues = $allowedValues;
    }

    public function getAllowedValuesQuery(): ?string
    {
        return $this->allowedValuesQuery;
    }

    public function setAllowedValuesQuery(?string $allowedValuesQuery): void
    {
        $this->allowedValuesQuery = $allowedValuesQuery;
    }
}
