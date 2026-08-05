<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Authentication\Model\User;
use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Application\Interfaces\SavableInterface;
use Pantono\Database\Traits\SavableModel;

#[DatabaseTable('product_review')]
class ProductReview implements SavableInterface
{
    use SavableModel;

    private ?int $id = null;
    private \DateTimeInterface $dateCreated;
    #[OneToOne(targetModel: Product::class), FieldName('product_id')]
    private ?Product $product = null;
    #[OneToOne(targetModel: User::class), FieldName('user_id')]
    private ?User $user = null;
    private ?string $title = null;
    private ?int $rating = null;
    private string $reviewContent;
    private bool $approved;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }

    public function getReviewContent(): string
    {
        return $this->reviewContent;
    }

    public function setReviewContent(string $reviewContent): void
    {
        $this->reviewContent = $reviewContent;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }
}
