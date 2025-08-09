<?php

namespace Pantono\Products\Model;

use DateTimeInterface;
use Pantono\Contracts\Attributes\Locator;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Products\Products;
use Pantono\Database\Traits\SavableModel;

#[Locator(methodName: 'getProductById', className: Products::class)]
class Product
{
    use SavableModel;

    private ?int $id = null;
    private ?DateTimeInterface $dateCreated = null;
    private ?DateTimeInterface $dateUpdated = null;
    #[Locator(methodName: 'getProductVersionById', className: Products::class), FieldName('draft_id')]
    private ProductVersion $draft;
    #[Locator(methodName: 'getProductVersionById', className: Products::class), FieldName('published_draft_id')]
    private ProductVersion $publishedDraft;
    private string $code;
    private string $slug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDateCreated(): ?DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?DateTimeInterface $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

    public function getDateUpdated(): ?DateTimeInterface
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?DateTimeInterface $dateUpdated): void
    {
        $this->dateUpdated = $dateUpdated;
    }

    public function getDraft(): ProductVersion
    {
        return $this->draft;
    }

    public function setDraft(ProductVersion $draft): void
    {
        $this->draft = $draft;
    }

    public function getPublishedDraft(): ProductVersion
    {
        return $this->publishedDraft;
    }

    public function setPublishedDraft(ProductVersion $publishedDraft): void
    {
        $this->publishedDraft = $publishedDraft;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
