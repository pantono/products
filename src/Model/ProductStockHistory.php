<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Authentication\Model\User;
use Pantono\Contracts\Attributes\Database\OneToOne;

#[DatabaseTable('product_stock_history')]
class ProductStockHistory
{
    private ?int $id = null;
    private int $productId;
    #[OneToOne(User::class), FieldName('user_id')]
    private User $user;
    private \DateTimeInterface $date;
    private int $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}
