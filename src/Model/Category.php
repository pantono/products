<?php

namespace Pantono\Products\Model;

use Pantono\Storage\Model\StoredFile;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\Locator;
use Pantono\Storage\FileStorage;
use Pantono\Products\Categories;
use Pantono\Contracts\Attributes\Lazy;
use Pantono\Contracts\Attributes\NoSave;

#[Locator(methodName: 'getCategoryById', className: Categories::class)]
class Category
{
    use SavableModel;

    private ?int $id = null;
    private string $title;
    private string $slug;
    private string $description;
    private ?int $parentId = null;
    private ?string $metaDescription = null;
    private ?string $metaTitle = null;
    private ?string $metaKeywords = null;
    private ?string $metaRobots = null;
    #[FieldName('image_id'), Locator(methodName: 'getImageById', className: FileStorage::class)]
    private ?StoredFile $image = null;
    #[FieldName('status_id'), Locator(methodName: 'getStatusById', className: Categories::class)]
    private ?CategoryStatus $status;
    #[Locator(methodName: 'getCategoryById', className: Categories::class), FieldName('parent_id'), Lazy, NoSave]
    private ?Category $parent = null;
    private int $displayOrder = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): void
    {
        $this->metaKeywords = $metaKeywords;
    }

    public function getMetaRobots(): ?string
    {
        return $this->metaRobots;
    }

    public function setMetaRobots(?string $metaRobots): void
    {
        $this->metaRobots = $metaRobots;
    }

    public function getImage(): ?StoredFile
    {
        return $this->image;
    }

    public function setImage(?StoredFile $image): void
    {
        $this->image = $image;
    }

    public function getStatus(): ?CategoryStatus
    {
        return $this->status;
    }

    public function setStatus(?CategoryStatus $status): void
    {
        $this->status = $status;
    }

    public function getParent(): ?Category
    {
        return $this->parent;
    }

    public function setParent(?Category $parent): void
    {
        $this->parent = $parent;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): void
    {
        $this->displayOrder = $displayOrder;
    }
}
