<?php

namespace Pantono\Products\Filter;

use Pantono\Contracts\Filter\PageableInterface;
use Pantono\Database\Traits\Pageable;
use Pantono\Authentication\Model\User;
use Pantono\Products\Model\Product;

class ProductFavouriteFilter implements PageableInterface
{
    use Pageable;

    private ?User $user = null;
    private ?Product $product = null;
    private ?\DateTimeInterface $dateCreatedStart = null;
    private ?\DateTimeInterface $dateCreatedEnd = null;
    private ?bool $deleted = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
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

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): void
    {
        $this->deleted = $deleted;
    }
}
