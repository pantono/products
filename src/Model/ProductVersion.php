<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;
use Pantono\Customers\Model\Company;
use Pantono\Core\Application\Traits\DiffableTrait;
use Pantono\Hydrator\Traits\FillableTrait;
use Pantono\Customers\Companies;

#[Locator(methodName: 'getProductById', className: Products::class)]
class ProductVersion
{
    use SavableModel, DiffableTrait, FillableTrait;

    private ?int $id = null;
    private int $productId;
    private \DateTimeImmutable $dateAdded;
    private \DateTimeImmutable $dateUpdated;
    #[FieldName('type_id'), Locator(methodName: 'getTypeById', className: Products::class)]
    private ProductType $type;
    private string $title;
    private string $description;
    #[FieldName('status_id'), Locator(methodName: 'getStatusById', className: Products::class)]
    private ProductStatus $status;
    #[FieldName('vat_rate_id'), Locator(methodName: 'getVatRateById', className: Products::class)]
    private ProductVatRate $vatRate;
    private float $weight;
    private int $itemsIncluded = 1;
    private int $stockHolding;
    private ?string $metaDescription;
    private ?string $metaTitle;
    private ?string $metaKeywords;
    private ?string $metaRobots;
    #[Locator(methodName: 'getBrandById', className: Products::class), FieldName('brand_id')]
    private ?ProductBrand $brand = null;
    #[Locator(methodName: 'getConditionById', className: Products::class), FieldName('condition_id')]
    private ?ProductCondition $condition = null;
    private float $price;
    private float $rrp;
    #[FieldName('company_id'), Locator(methodName: 'getCompanyById', className: Companies::class)]
    private ?Company $company = null;
    /**
     * @var ProductImage[]
     */
    #[Locator(methodName: 'getImagesForProduct', className: Products::class), FieldName('$this')]
    private array $images = [];
    #[Locator(methodName: 'getCategoriesForProduct', className: Products::class), FieldName('$this')]
    private array $categories = [];
    /**
     * @var ProductVersion[]
     */
    #[Locator(methodName: 'getRelatedProducts', className: Products::class), FieldName('$this')]
    private array $related = [];
    /**
     * @var Flag[]
     */
    #[Locator(methodName: 'getFlagsForProduct', className: ProductVersion::class), FieldName('$this')]
    private array $flags;

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

    public function getItemsIncluded(): int
    {
        return $this->itemsIncluded;
    }

    public function setItemsIncluded(int $itemsIncluded): void
    {
        $this->itemsIncluded = $itemsIncluded;
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

    public function getBrand(): ?ProductBrand
    {
        return $this->brand;
    }

    public function setBrand(?ProductBrand $brand): void
    {
        $this->brand = $brand;
    }

    public function getCondition(): ?ProductCondition
    {
        return $this->condition;
    }

    public function setCondition(?ProductCondition $condition): void
    {
        $this->condition = $condition;
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
