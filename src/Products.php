<?php

namespace Pantono\Products;

use Pantono\Products\Repository\ProductsRepository;
use Pantono\Hydrator\Hydrator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pantono\Products\Model\ProductType;
use Pantono\Products\Model\ProductVatRate;
use Pantono\Products\Model\ProductStatus;
use Pantono\Products\Model\ProductVersion;
use Pantono\Products\Model\ProductImage;
use Pantono\Products\Model\ProductCategory;
use Pantono\Products\Event\PreProductVersionSaveEvent;
use Pantono\Products\Event\PostProductVersionSaveEvent;
use Pantono\Products\Filter\ProductFilter;
use Pantono\Products\Model\Flag;
use Pantono\Products\Model\Product;
use Pantono\Products\Event\PreProductSaveEvent;
use Pantono\Products\Event\PostProductSaveEvent;
use Pantono\Products\Model\ProductBrand;
use Pantono\Products\Model\ProductCondition;
use Pantono\Products\Event\PreBrandSaveEvent;
use Pantono\Products\Event\PostBrandSaveEvent;

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

    public function getProductVersionById(int $id): ?ProductVersion
    {
        return $this->hydrator->hydrate(ProductVersion::class, $this->repository->getProductVersionById($id));
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

    /**
     * @return ProductVatRate[]
     */
    public function getAllVatRates(): array
    {
        return $this->hydrator->hydrateSet(ProductVatRate::class, $this->repository->getAllVatRates());
    }

    public function getStatusById(int $id): ?ProductStatus
    {
        return $this->hydrator->hydrate(ProductStatus::class, $this->repository->getStatusById($id));
    }

    /**
     * @return ProductImage[]
     */
    public function getImagesForProduct(ProductVersion $product): array
    {
        return $this->hydrator->hydrateSet(ProductImage::class, $this->repository->getImagesForProduct($product));
    }

    public function getProductCategoryById(int $id): ?ProductCategory
    {
        return $this->hydrator->hydrate(ProductCategory::class, $this->repository->getProductCategoryById($id));
    }

    /**
     * @return ProductCategory[]
     */
    public function getCategoriesForProduct(ProductVersion $product): array
    {
        return $this->hydrator->hydrateSet(ProductCategory::class, $this->repository->getCategoriesForProduct($product));
    }

    public function getProductImageById(int $id): ?ProductImage
    {
        return $this->hydrator->hydrate(ProductImage::class, $this->repository->getProductImageById($id));
    }

    /**
     * @return ProductVersion[]
     */
    public function getRelatedProducts(ProductVersion $product): array
    {
        return $this->hydrator->hydrateSet(ProductVersion::class, $this->repository->getRelatedProducts($product));
    }

    public function getProductBySlug(string $slug): ?ProductVersion
    {
        return $this->hydrator->hydrate(ProductVersion::class, $this->repository->getProductBySlug($slug));
    }

    public function getFlagsForProduct(ProductVersion $product): array
    {
        return $this->hydrator->hydrateSet(Flag::class, $this->repository->getFlagsForProduct($product));
    }

    public function getFlagById(int $id): ?Flag
    {
        return $this->hydrator->hydrate(Flag::class, $this->repository->getFlagById($id));
    }

    /**
     * @return Flag[]
     */
    public function getAllFlags(): array
    {
        return $this->hydrator->hydrateSet(Flag::class, $this->repository->getAllFlags());
    }


    public function getBrandById(int $id): ?ProductBrand
    {
        return $this->hydrator->hydrate(ProductBrand::class, $this->repository->getBrandById($id));
    }

    /**
     * @return ProductBrand[]
     */
    public function getAllBrands(): array
    {
        return $this->hydrator->hydrateSet(ProductBrand::class, $this->repository->getAllBrands());
    }

    public function getConditionById(int $id): ?ProductCondition
    {
        return $this->hydrator->hydrate(ProductCondition::class, $this->repository->getConditionById($id));
    }

    /**
     * @return ProductCondition[]
     */
    public function getAllConditions(): array
    {
        return $this->hydrator->hydrateSet(ProductCondition::class, $this->repository->getAllConditions());
    }

    /**
     * @return ProductVersion[]
     */
    public function getProductsByFilter(ProductFilter $filter): array
    {
        return $this->hydrator->hydrateSet(ProductVersion::class, $this->repository->getProductsByFilter($filter));
    }

    public function saveProductVersion(ProductVersion $product): void
    {
        $previous = $product->getId() ? $this->getProductVersionById($product->getId()) : null;
        $event = new PreProductVersionSaveEvent();
        $event->setCurrent($product);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);

        $this->repository->saveProductVersion($product);

        $event = new PostProductVersionSaveEvent();
        $event->setCurrent($product);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);
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

    public function saveBrand(ProductBrand $brand): void
    {
        $previous = $brand->getId() ? $this->getBrandById($brand->getId()) : null;
        $event = new PreBrandSaveEvent();
        $event->setCurrent($brand);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);

        $this->repository->saveBrand($brand);

        $event = new PostBrandSaveEvent();
        $event->setCurrent($brand);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);
    }
}
