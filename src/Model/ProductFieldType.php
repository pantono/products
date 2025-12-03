<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Filter;
use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;
use Pantono\Contracts\Attributes\Lazy;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;

class ProductFieldType
{
    use SavableModel;

    private ?int $id = null;
    private string $name;
    private string $label;
    private string $type;
    private ?string $regex = null;
    #[Filter('json_decode')]
    private array $allowedValues = [];
    private ?string $allowedValuesQuery = null;
    /**
     * @var array<string,value>
     */
    #[Locator(methodName: 'populateAllowedValues', className: Products::class), FieldName('$this'), Lazy]
    private ?array $allowedValuesFromQuery = null;
    private bool $uiVisible = true;

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

    public function getAllowedValuesFromQuery(): ?array
    {
        return $this->allowedValuesFromQuery;
    }

    public function setAllowedValuesFromQuery(?array $allowedValuesFromQuery): void
    {
        $this->allowedValuesFromQuery = $allowedValuesFromQuery;
    }

    public function isUiVisible(): bool
    {
        return $this->uiVisible;
    }

    public function setUiVisible(bool $uiVisible): void
    {
        $this->uiVisible = $uiVisible;
    }

    public function getComputedAllowedValues(): array
    {
        if ($this->getAllowedValues()) {
            $values = $this->getAllowedValuesFromQuery();
            if ($values === null) {
                return $this->getAllowedValues();
            }
            return $values;
        }
        return $this->getAllowedValues();
    }

    public function isValid(mixed $value): bool
    {
        if ($this->getRegex() !== null) {
            if (preg_match($this->getRegex(), $value) === 0) {
                return false;
            }
        }

        if ($this->getAllowedValuesQuery() || !empty($this->getAllowedValues())) {
            return in_array($value, $this->getComputedAllowedValues());
        }
        return true;
    }
}
