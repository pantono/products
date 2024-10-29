<?php

namespace Pantono\Products;

use Pantono\Products\Repository\ProductsRepository;
use Pantono\Hydrator\Hydrator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pantono\Products\Model\ProductType;
use Pantono\Products\Model\ProductVatRate;
use Pantono\Products\Model\ProductStatus;
use Pantono\Products\Model\Product;
use Pantono\Products\Model\ProductImage;
use Pantono\Products\Model\ProductCategory;
use Pantono\Products\Event\PreProductSaveEvent;
use Pantono\Products\Event\PostProductSaveEvent;
use Pantono\Products\Model\Category;

class Products
{
    private ProductsRepository $repository;
    private Hydrator $hydrator;
    private EventDispatcher $dispatcher;

    public function __construct(ProductsRepository $repository, Hydrator $hydrator, EventDispatcher $dispatcher)
    {
        $this->repository = $repository;
        $this->hydrator = $hydrator;
        $this->dispatcher = $dispatcher;
    }

    public function getProductById(int $id): ?Product
    {
        return $this->hydrator->hydrate(Product::class, $this->repository->getProductById($id));
    }

    public function getProductTypeById(int $id): ?ProductType
    {
        return $this->hydrator->hydrate(ProductType::class, $this->repository->getProductTypeById($id));
    }

    public function getVatRateById(int $id): ?ProductVatRate
    {
        return $this->hydrator->hydrate(ProductVatRate::class, $this->repository->getVatRateById($id));
    }

    public function getStatusById(int $id): ?ProductStatus
    {
        return $this->hydrator->hydrate(ProductStatus::class, $this->repository->getStatusById($id));
    }

    /**
     * @return ProductImage[]
     */
    public function getImagesForProduct(Product $product): array
    {
        return $this->hydrator->hydrateSet(ProductImage::class, $this->repository->getImagesForProduct($product));
    }

    public function getProductCategoryById(int $id): ?ProductCategory
    {
        return $this->hydrator->hydrate(ProductCategory::class, $this->repository->getProductCategoryById($id));
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->hydrator->hydrate(Category::class, $this->repository->getCategoryById($id));
    }

    /**
     * @return ProductCategory[]
     */
    public function getCategoriesForProduct(Product $product): array
    {
        return $this->hydrator->hydrateSet(ProductCategory::class, $this->repository->getCategoriesForProduct($product));
    }

    public function getProductImageById(int $id): ?ProductImage
    {
        return $this->hydrator->hydrate(ProductImage::class, $this->repository->getProductImageById($id));
    }

    /**
     * @return Product[]
     */
    public function getRelatedProducts(Product $product): array
    {
        return $this->hydrator->hydrateSet(Product::class, $this->repository->getRelatedProducts($product));
    }

    public function saveProduct(Product $product): void
    {
        $previous = $product->getId() ? $this->getProductById($product->getId()) : null;
        $event = new PreProductSaveEvent();
        $event->setCurrent($product);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);

        $this->repository->saveProduct($product);

        $event = new PostProductSaveEvent();
        $event->setCurrent($product);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);
    }
}
