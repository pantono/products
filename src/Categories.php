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
        return $this->hydrator->hydrate(Category::class, $this->repository->getCategoryById($id));
    }

    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->hydrator->hydrate(Category::class, $this->repository->getCategoryBySlug($slug));
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
}
