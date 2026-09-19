<?php

namespace Pantono\Products\Filter;

use Pantono\Contracts\Filter\PageableInterface;
use Pantono\Database\Traits\Pageable;
use Pantono\Database\Filter\SortableFilter;
use Pantono\Products\Model\DiscountBase;

class DiscountFilter extends SortableFilter implements PageableInterface
{
    use Pageable;

    private ?string $search = null;
    private ?bool $minSpendBetween = null;
    private ?DiscountBase $base = null;
    private ?bool $active = null;

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getMinSpendBetween(): ?bool
    {
        return $this->minSpendBetween;
    }

    public function setMinSpendBetween(?bool $minSpendBetween): void
    {
        $this->minSpendBetween = $minSpendBetween;
    }

    public function getBase(): ?DiscountBase
    {
        return $this->base;
    }

    public function setBase(?DiscountBase $base): void
    {
        $this->base = $base;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    public function getSortableFields(): array
    {
        return [
            ''
        ];
        // TODO: Implement getSortableFields() method.
    }
}
