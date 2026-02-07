<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\Lazy;
use Pantono\Contracts\Attributes\NoSave;
use Pantono\Images\Model\Image;
use Pantono\Contracts\Attributes\EagerLoad;
use Pantono\Contracts\Attributes\Database\OneToOne;
use Pantono\Contracts\Attributes\Database\OneToMany;
use Pantono\Contracts\Attributes\DatabaseTable;

#[DatabaseTable(table: 'category', idColumn: 'id'), EagerLoad]
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
    #[NoSave]
    private ?string $breadcrumb = null;
    #[FieldName('image_id'), OneToOne(targetModel: Image::class)]
    private ?Image $image = null;
    #[FieldName('status_id'), OneToOne(targetModel: CategoryStatus::class)]
    private ?CategoryStatus $status;
    #[FieldName('parent_id'), OneToOne(targetModel: Category::class)]
    private ?Category $parent = null;
    /**
     * @var Category[]
     */
    #[Lazy, NoSave, OneToMany(targetModel: Category::class, mappedBy: 'parent_id')]
    private array $children = [];
    private int $displayOrder = 0;
    /**
     * @var CategoryField[]
     */
    #[Lazy, OneToMany(targetModel: CategoryField::class, mappedBy: 'category_id')]
    private array $fields = [];

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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): void
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

    public function getBreadcrumb(): ?string
    {
        return $this->breadcrumb;
    }

    public function setBreadcrumb(?string $breadcrumb): void
    {
        $this->breadcrumb = $breadcrumb;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): void
    {
        $this->fields = $fields;
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

    public function setFieldValue(CategoryFieldType $type, string $value): void
    {
        foreach ($this->getFields() as $field) {
            if ($field->getType()->getId() === $type->getId()) {
                $field->setValue($value);
                return;
            }
        }
        $categoryField = new CategoryField();
        $categoryField->setType($type);
        $categoryField->setValue($value);
        $this->fields[] = $categoryField;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function setChildren(array $children): void
    {
        $this->children = $children;
    }
}
