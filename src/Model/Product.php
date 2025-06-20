<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;
use Pantono\Customers\Model\Company;

#[Locator(methodName: 'getProductById', className: Products::class)]
class Product
{
    use SavableModel;

    private ?int $id = null;
    private \DateTimeImmutable $dateAdded;
    private \DateTimeImmutable $dateUpdated;
    private ProductType $type;
    private string $title;
    private string $code;
    private string $slug;
    private string $description;
    #[FieldName('status_id'), Locator(methodName: 'getStatusById', className: Products::class)]
    private ProductStatus $status;
    #[FieldName('vat_rate_id'), Locator(methodName: 'getVatRateById', className: Products::class)]
    private ProductVatRate $vatRate;
    private float $weight;
    private int $stockHolding;
    private ?string $metaDescription;
    private ?string $metaTitle;
    private ?string $metaKeywords;
    private ?string $metaRobots;
    private ?int $brandId;
    private ?int $conditionId;
    private float $price;
    private float $rrp;
    private ?Company $company = null;
    /**
     * @var ProductImage[]
     */
    #[Locator(methodName: 'getImagesForProduct', className: Products::class), FieldName('$this')]
    private array $images = [];
    #[Locator(methodName: 'getCategoriesForProduct', className: Products::class), FieldName('$this')]
    private array $categories = [];
    /**
     * @var Product[]
     */
    #[Locator(methodName: 'getRelatedProducts', className: Products::class), FieldName('$this')]
    private array $related = [];
    /**
     * @var Flag[]
     */
    #[Locator(methodName: 'getFlagsForProduct', className: Product::class), FieldName('$this')]
    private array $flags;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDateAdded(): \DateTimeImmutable
    {
        return $this->dateAdded;
    }

    public function setDateAdded(\DateTimeImmutable $dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }

    public function getDateUpdated(): \DateTimeImmutable
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(\DateTimeImmutable $dateUpdated): void
    {
        $this->dateUpdated = $dateUpdated;
    }

    public function getType(): ProductType
    {
        return $this->type;
    }

    public function setType(ProductType $type): void
    {
        $this->type = $type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStatus(): ProductStatus
    {
        return $this->status;
    }

    public function setStatus(ProductStatus $status): void
    {
        $this->status = $status;
    }

    public function getVatRate(): ProductVatRate
    {
        return $this->vatRate;
    }

    public function setVatRate(ProductVatRate $vatRate): void
    {
        $this->vatRate = $vatRate;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function getStockHolding(): int
    {
        return $this->stockHolding;
    }

    public function setStockHolding(int $stockHolding): void
    {
        $this->stockHolding = $stockHolding;
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

    public function getBrandId(): ?int
    {
        return $this->brandId;
    }

    public function setBrandId(?int $brandId): void
    {
        $this->brandId = $brandId;
    }

    public function getConditionId(): ?int
    {
        return $this->conditionId;
    }

    public function setConditionId(?int $conditionId): void
    {
        $this->conditionId = $conditionId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    public function getRrp(): float
    {
        return $this->rrp;
    }

    public function setRrp(float $rrp): void
    {
        $this->rrp = $rrp;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function getRelated(): array
    {
        return $this->related;
    }

    public function setRelated(array $related): void
    {
        $this->related = $related;
    }

    public function getFlags(): array
    {
        return $this->flags;
    }

    public function setFlags(array $flags): void
    {
        $this->flags = $flags;
    }
}
