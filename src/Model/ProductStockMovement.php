<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Authentication\Model\User;
use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Contracts\Application\Interfaces\SavableInterface;

#[DatabaseTable('product_stock_movement')]
class ProductStockMovement implements SavableInterface
{
    use SavableModel;

    private ?int $id = null;
    private int $productId;
    #[OneToOne(User::class), FieldName('user_id')]
    private User $user;
    private ?int $orderId = null;
    private \DateTimeInterface $date;
    private int $value;
    private ?string $comments = null;

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

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): void
    {
        $this->comments = $comments;
    }
}
