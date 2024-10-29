<?php

namespace Pantono\Products\Model;

use Pantono\Storage\Model\StoredFile;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;

#[Locator(methodName: 'getProductImageBydId', className: Products::class)]
class ProductImage
{
    use SavableModel;

    private ?int $id = null;
    private int $productId;
    #[FieldName('image_id')]
    private StoredFile $image;
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

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getImage(): StoredFile
    {
        return $this->image;
    }

    public function setImage(StoredFile $image): void
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
