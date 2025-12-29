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
use Pantono\Products\Model\ProductFieldType;
use Pantono\Utilities\EphemeralCacheHelper;
use Pantono\Products\Model\ProductField;
use Pantono\Products\Event\PreProductFieldTypeSaveEvent;
use Pantono\Products\Event\PostProductFieldTypeSaveEvent;

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
        return $this->hydrator->hydrateCached('product_type_' . $id, ProductType::class, function () use ($id) {
            return $this->repository->getProductTypeById($id);
        });
    }

    public function getVatRateById(int $id): ?ProductVatRate
    {
        return $this->hydrator->hydrateCached('vat_rate_' . $id, ProductVatRate::class, function () use ($id) {
            return $this->repository->getVatRateById($id);
        });
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
        return $this->hydrator->hydrateCached('product_status_' . $id, ProductStatus::class, function () use ($id) {
            return $this->repository->getStatusById($id);
        });
    }

    public function getAllStatuses(): array
    {
        return $this->hydrator->hydrateSet(ProductStatus::class, $this->repository->getAllStatuses());
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

    public function getProductBySlug(string $slug): ?Product
    {
        return $this->hydrator->hydrate(Product::class, $this->repository->getProductBySlug($slug));
    }

    public function getFlagsForProductVersion(ProductVersion $product): array
    {
        return $this->hydrator->hydrateSet(Flag::class, $this->repository->getFlagsForProductVersion($product));
    }

    public function getFlagById(int $id): ?Flag
    {
        return $this->hydrator->hydrateCached('flag_' . $id, Flag::class, function () use ($id) {
            $this->repository->getFlagById($id);
        });
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
        return $this->hydrator->hydrateCached('product_brand_' . $id, ProductBrand::class, function () use ($id) {
            $this->repository->getBrandById($id);
        });
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
        return $this->hydrator->hydrateCached('product_condition_' . $id, ProductCondition::class, function () use ($id) {
            $this->repository->getConditionById($id);
        });
    }

    /**
     * @return ProductCondition[]
     */
    public function getAllConditions(): array
    {
        return $this->hydrator->hydrateSet(ProductCondition::class, $this->repository->getAllConditions());
    }

    public function getProductsByFilter(ProductFilter $filter): array
    {
        return $this->hydrator->hydrateSet(Product::class, $this->repository->getProductsByFilter($filter));
    }


    public function getFieldTypeById(int $id): ?ProductFieldType
    {
        return $this->hydrator->hydrateCached('product_field_type_' . $id, ProductFieldType::class, function () use ($id) {
            return $this->repository->getFieldTypeById($id);
        });
    }

    /**
     * @return ProductField[]
     */
    public function getFieldsForProductVersion(ProductVersion $product): array
    {
        return $this->hydrator->hydrateSet(ProductField::class, $this->repository->getFieldsForProductVersion($product));
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

    public function saveProductFieldType(ProductFieldType $type): void
    {
        $previous = $type->getId() ? $this->getFieldTypeById($type->getId()) : null;
        $event = new PreProductFieldTypeSaveEvent();
        $event->setCurrent($type);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);

        $this->repository->saveProductFieldType($type);

        $event = new PostProductFieldTypeSaveEvent();
        $event->setCurrent($type);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);
    }
}
