<?php

namespace Pantono\Products\Model;

use Pantono\Storage\Model\StoredFile;
use Pantono\Contracts\Attributes\FieldName;

class Category
{
    private ?int $id = null;
    private string $title;
    private string $slug;
    private string $description;
    private ?string $metaDescription = null;
    private ?string $metaTitle = null;
    private ?string $metaKeywords = null;
    private ?string $metaRobots = null;
    #[FieldName('image_id')]
    private ?StoredFile $image = null;

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
}
