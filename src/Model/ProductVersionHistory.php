<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\ProductHistory;

#[Locator(methodName: 'getHistoryById', className: ProductHistory::class)]
class ProductVersionHistory
{
    private ?int $id;
    private \DateTimeInterface $date;
    private int $productVersionId;
    private int $userId;
    private string $userName;
    private string $entry;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getProductVersionId(): int
    {
        return $this->productVersionId;
    }

    public function setProductVersionId(int $productVersionId): void
    {
        $this->productVersionId = $productVersionId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getEntry(): string
    {
        return $this->entry;
    }

    public function setEntry(string $entry): void
    {
        $this->entry = $entry;
    }
}
