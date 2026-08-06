<?php

namespace Pantono\Products\Filter;

use Pantono\Contracts\Filter\PageableInterface;
use Pantono\Database\Traits\Pageable;
use Pantono\Products\Model\Product;
use Pantono\Authentication\Model\User;

class ReviewFilter implements PageableInterface
{
    use Pageable;

    private ?Product $product = null;
    private ?bool $approved = null;
    private ?\DateTimeInterface $dateCreatedStart = null;
    private ?\DateTimeInterface $dateCreatedEnd = null;
    private ?User $user = null;
    private string $orderColumn = 'date_created';
    private string $orderDirection = 'DESC';

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    public function getApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(?bool $approved): void
    {
        $this->approved = $approved;
    }

    public function getDateCreatedStart(): ?\DateTimeInterface
    {
        return $this->dateCreatedStart;
    }

    public function setDateCreatedStart(?\DateTimeInterface $dateCreatedStart): void
    {
        $this->dateCreatedStart = $dateCreatedStart;
    }

    public function getDateCreatedEnd(): ?\DateTimeInterface
    {
        return $this->dateCreatedEnd;
    }

    public function setDateCreatedEnd(?\DateTimeInterface $dateCreatedEnd): void
    {
        $this->dateCreatedEnd = $dateCreatedEnd;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getOrderColumn(): string
    {
        return $this->orderColumn;
    }

    public function setOrderColumn(string $orderColumn): void
    {
        if (!in_array(strtolower($orderColumn), ['date_created', 'user', 'approved', 'rating'])) {
            throw new \InvalidArgumentException('Invalid order column');
        }
        $this->orderColumn = $orderColumn;
    }

    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }

    public function setOrderDirection(string $orderDirection): void
    {
        if (strtolower($orderDirection) !== 'asc' && strtolower($orderDirection) !== 'desc') {
            throw new \InvalidArgumentException('Invalid order direction');
        }
        $this->orderDirection = $orderDirection;
    }
}
