<?php

namespace Pantono\Products\Filter;

use Pantono\Database\Traits\Pageable;
use Pantono\Contracts\Filter\PageableInterface;
use Pantono\Database\Traits\ColumnFilter;

class CategoryFilter implements PageableInterface
{
    use Pageable, ColumnFilter;

    private ?string $slug = null;
    private ?string $search = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }
}
