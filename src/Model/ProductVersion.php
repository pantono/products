<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;
use Pantono\Customers\Model\Company;
use Pantono\Core\Application\Traits\DiffableTrait;
use Pantono\Hydrator\Traits\FillableTrait;
use Pantono\Products\Discounts;
use Pantono\Contracts\Attributes\NoSave;
use Pantono\Contracts\Attributes\Lazy;
use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Contracts\Attributes\Database\OneToMany;
use Pantono\Utilities\StringUtilities;

#[DatabaseTable(table: 'product_version', idColumn: 'id')]
class ProductVersion
{
    use SavableModel, DiffableTrait, FillableTrait;

    private ?int $id = null;
    private ?int $productId = null;
    private \DateTimeImmutable $dateAdded;
    private \DateTimeImmutable $dateUpdated;
    #[FieldName('type_id'), OneToOne(targetModel: ProductType::class)]
    private ProductType $type;
    private string $title;
    private string $description;
    #[FieldName('status_id'), OneToOne(targetModel: ProductStatus::class)]
    private ProductStatus $status;
    #[FieldName('vat_rate_id'), OneToOne(targetModel: ProductVatRate::class)]
    private ProductVatRate $vatRate;
    private float $weight;
    private int $itemsIncluded = 1;
    private ?string $metaDescription = null;
    private ?string $metaTitle = null;
    private ?string $metaKeywords = null;
    private ?string $metaRobots = null;
    #[OneToOne(targetModel: ProductBrand::class), FieldName('brand_id')]
    private ?ProductBrand $brand = null;
    #[OneToOne(targetModel: ProductCondition::class), FieldName('condition_id')]
    private ?ProductCondition $condition = null;
    private float $price;
    private float $rrp;
    #[FieldName('company_id'), OneToOne(targetModel: Company::class)]
    private ?Company $company = null;
    /**
     * @var ProductImage[]
     */
    #[OneToMany(targetModel: ProductImage::class, mappedBy: 'version_id'), FieldName('id')]
    private array $images = [];
    /**
     * @var ProductCategory[]
     */
    #[OneToMany(targetModel: ProductCategory::class, mappedBy: 'version_id'), FieldName('id')]
    private array $categories = [];
    /**
     * @var ProductVersion[]
     */
    #[Locator(methodName: 'getRelatedProducts', className: Products::class), FieldName('$this')]
    private array $related = [];
    /**
     * @var Flag[]
     */
    #[Locator(methodName: 'getFlagsForProductVersion', className: Products::class), FieldName('$this')]
    private array $flags = [];
    /**
     * @var SpecialOffer[]
     */
    #[Locator(methodName: 'getOffersForProductVersion', className: Discounts::class), FieldName('$this'), Lazy]
    private array $offers = [];
    #[NoSave]
    private ?ProductPrice $priceBreakdown = null;
    /**
     * @var ProductField[]
     */
    #[Locator(methodName: 'getFieldsForProductVersion', className: Products::class), FieldName('$this')]
    private array $fields = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): void
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

    public function addImage(ProductImage $image): void
    {
        $this->images[] = $image;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function addCategory(ProductCategory $category): void
    {
        $this->categories[] = $category;
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

    public function getOffers(): array
    {
        return $this->offers;
    }

    public function setOffers(array $offers): void
    {
        $this->offers = $offers;
    }

    /**
     * @return SpecialOffer[]
     */
    public function getActiveOffers(): array
    {
        return array_filter($this->offers, function (SpecialOffer $offer) {
            return $offer->isActive();
        });
    }

    public function getPriceBreakdown(): ProductPrice
    {
        if (!$this->priceBreakdown) {
            $this->priceBreakdown = new ProductPrice($this);
        }
        return $this->priceBreakdown;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function addField(ProductField $field): void
    {
        $this->fields[] = $field;
    }

    public function addFieldByType(ProductFieldType $type, mixed $value): void
    {
        foreach ($this->getFields() as $field) {
            if ($field->getType()->getId() === $type->getId()) {
                $field->setValue($value);
                return;
            }
        }
        $field = new ProductField();
        $field->setType($type);
        $field->setValue($value);
        $this->fields[] = $field;
    }

    public function getFieldValueByName(string $name): mixed
    {
        foreach ($this->getFields() as $field) {
            if ($field->getType()->getName() === $name) {
                return $field->getCastedValue();
            }
        }
        return null;
    }

    public function isOnOffer(): bool
    {
        return !empty($this->getActiveOffers());
    }

    public function toArray(): array
    {
        $data = [
            'productId' => $this->getProductId(),
            'type' => $this->getType()->getName(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus()->getName(),
            'vatRate' => $this->getVatRate()->getName(),
            'weight' => $this->getWeight(),
            'itemsIncluded' => $this->getItemsIncluded(),
            'metaDescription' => $this->getMetaDescription(),
            'metaTitle' => $this->getMetaTitle(),
            'metaKeywords' => $this->getMetaKeywords(),
            'metaRobots' => $this->getMetaRobots(),
            'brand' => $this->getBrand()?->getName(),
            'condition' => $this->getCondition()?->getName(),
            'price' => $this->getPrice(),
            'rrp' => $this->getRrp(),
            'company' => $this->getCompany()?->getName(),
        ];
        foreach ($this->getFields() as $field) {
            $name = $field->getType()->getName();
            $name = str_replace('_', ' ', $name);
            $name = ucwords($name);
            $name = StringUtilities::camelCase($name);
            $data[$name] = $field->getCastedValue();
        }
        foreach ($this->getImages() as $index => $image) {
            $data['image_' . ($index + 1)] = $image->getImage()->getFile()->getOriginalFilename();
            if ($image->isMainImage()) {
                $data['main_image'] = $image->getImage()->getFile()->getOriginalFilename();
            }
        }
        return $data;
    }
}
