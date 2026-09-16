<?php

namespace Pantono\Products\Filter;

use Pantono\Database\Traits\Pageable;
use Pantono\Contracts\Filter\PageableInterface;
use Pantono\Products\Model\ProductStatus;
use Pantono\Database\Traits\ColumnFilter;
use Pantono\Contracts\Application\Interfaces\SortableInterface;
use Pantono\Database\Filter\SortableFilter;

class ProductFilter extends SortableFilter implements PageableInterface, SortableInterface
{
    use Pageable, ColumnFilter;

    private ?ProductStatus $status = null;
    private ?string $search = null;
    /**
     * @var array<int>
     */
    private array $categoryIds = [];
    private ?string $orderBy = null;

    public function getSortableFields(): array
    {
        return [
            'p.id', 'p.stock_holding', 'p.code', 'p.slug', 'p.date_created',
            'published.title', 'published.price', 'published.date_added', 'published_status.name', 'published.weight', 'published.rrp',
            'draft.title', 'draft.price', 'draft.date_added', 'draft_status.name', 'draft.weight', 'draft.rrp',
        ];
    }

    public function getStatus(): ?ProductStatus
    {
        return $this->status;
    }

    public function setStatus(?ProductStatus $status): void
    {
        $this->status = $status;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    public function setCategoryIds(array $categoryIds): void
    {
        $this->categoryIds = $categoryIds;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function setOrderBy(?string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }
}
