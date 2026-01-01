<?php

namespace Pantono\Products;

use Pantono\Products\Repository\CategoriesRepository;
use Pantono\Hydrator\Hydrator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pantono\Products\Model\Category;
use Pantono\Products\Event\PreCategorySaveEvent;
use Pantono\Products\Event\PostCategorySaveEvent;
use Pantono\Products\Filter\CategoryFilter;
use Pantono\Products\Model\CategoryStatus;
use Pantono\Products\Model\CategoryFieldType;
use Pantono\Products\Event\PreCategoryFieldTypeSaveEvent;
use Pantono\Products\Event\PostCategoryFieldTypeSaveEvent;
use Pantono\Products\Model\CategoryField;

class Categories
{
    private CategoriesRepository $repository;
    private Hydrator $hydrator;
    private EventDispatcher $dispatcher;

    public function __construct(CategoriesRepository $repository, Hydrator $hydrator, EventDispatcher $dispatcher)
    {
        $this->repository = $repository;
        $this->hydrator = $hydrator;
        $this->dispatcher = $dispatcher;
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->hydrator->hydrateCached('category_' . $id, Category::class, function () use ($id) {
            return $this->repository->getCategoryById($id);
        });
    }

    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->hydrator->hydrateCached('category_slug_' . $slug, Category::class, function () use ($slug) {
            return $this->repository->getCategoryBySlug($slug);
        });
    }

    /**
     * @return Category[]
     */
    public function getCategoriesByFilter(CategoryFilter $filter): array
    {
        return $this->hydrator->hydrateSet(Category::class, $this->repository->getCategoriesByFilter($filter));
    }

    public function getStatusById(int $id): ?CategoryStatus
    {
        return $this->hydrator->hydrateCached('category_status_' . $id, CategoryStatus::class, fn() => $this->repository->getStatusById($id));
    }

    public function saveCategory(Category $category): void
    {
        $previous = $category->getId() ? $this->getCategoryById($category->getId()) : null;
        $event = new PreCategorySaveEvent();
        $event->setCurrent($category);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);

        $this->repository->saveCategory($category);

        $event = new PostCategorySaveEvent();
        $event->setCurrent($category);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);
    }

    public function getFieldTypeById(int $id): ?CategoryFieldType
    {
        return $this->hydrator->hydrateCached('category_field_type_' . $id, CategoryFieldType::class, function () use ($id) {
            return $this->repository->getFieldTypeById($id);
        });
    }

    public function getFieldTypeByName(string $name): ?CategoryFieldType
    {
        return $this->hydrator->hydrate(CategoryFieldType::class, $this->repository->getFieldTypeByName($name));
    }

    /**
     * @param Category $category
     * @return CategoryField[]
     */
    public function getFieldsForCategory(Category $category): array
    {
        return $this->hydrator->hydrateSet(CategoryField::class, $this->repository->getFieldsForCategory($category));
    }

    public function saveFieldType(CategoryFieldType $type): void
    {
        $previous = $type->getId() ? $this->getFieldTypeById($type->getId()) : null;
        $event = new PreCategoryFieldTypeSaveEvent();
        $event->setCurrent($type);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);

        $this->repository->saveCategoryFieldType($type);

        $event = new PostCategoryFieldTypeSaveEvent();
        $event->setCurrent($type);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);
    }

    /**
     * @return Category[]
     */
    public function getChildrenForCategory(Category $category): array
    {
        if (!$category->getId()) {
            return [];
        }
        return $this->hydrator->hydrateSetCached('category_children_' . $category->getId(), Category::class, function () use ($category) {
            return $this->repository->getChildren($category->getId());
        });
    }
}
