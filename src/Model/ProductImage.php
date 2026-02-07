<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;
use Pantono\Images\Model\Image;
use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\OneToOne;

#[DatabaseTable(table: 'product_image', idColumn: 'id')]
class ProductImage
{
    use SavableModel;

    private ?int $id = null;
    private int $versionId;
    #[FieldName('image_id'), OneToOne(targetModel: Image::class)]
    private ?Image $image = null;
    private bool $mainImage;
    private bool $deleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getVersionId(): int
    {
        return $this->versionId;
    }

    public function setVersionId(int $versionId): void
    {
        $this->versionId = $versionId;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): void
    {
        $this->image = $image;
    }

    public function isMainImage(): bool
    {
        return $this->mainImage;
    }

    public function setMainImage(bool $mainImage): void
    {
        $this->mainImage = $mainImage;
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
