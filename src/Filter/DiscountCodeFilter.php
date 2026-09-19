<?php

namespace Pantono\Products\Filter;

use Pantono\Products\Model\Discount;
use Pantono\Products\Model\DiscountBase;
use Pantono\Database\Filter\SortableFilter;
use Pantono\Contracts\Filter\PageableInterface;
use Pantono\Database\Traits\Pageable;

class DiscountCodeFilter extends SortableFilter implements PageableInterface
{
    use Pageable;

    private ?Discount $discount = null;
    private ?DiscountBase $discountBase = null;
    private ?string $search = null;
    private ?\DateTimeInterface $date = null;

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): void
    {
        $this->discount = $discount;
    }

    public function getDiscountBase(): ?DiscountBase
    {
        return $this->discountBase;
    }

    public function setDiscountBase(?DiscountBase $discountBase): void
    {
        $this->discountBase = $discountBase;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getSortableFields(): array
    {
        return [
            'c.id', 'c.code', 'c.start_date', 'c.end_date', 'c.max_uses'
        ];
    }
}
