<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Application\Interfaces\SavableInterface;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Authentication\Model\User;
use Pantono\Contracts\Attributes\DatabaseTable;

#[DatabaseTable('product_favourite')]
class ProductFavourite implements SavableInterface
{
    use SavableModel;

    private ?int $id = null;
    private \DateTimeInterface $dateCreated;
    #[OneToOne(targetModel: Product::class), FieldName('product_id')]
    private ?Product $product = null;
    #[OneToOne(targetModel: User::class), FieldName('user_id')]
    private ?User $user = null;
    private bool $deleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDateCreated(): \DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }
}
