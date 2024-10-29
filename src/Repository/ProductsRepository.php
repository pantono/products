<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\MysqlRepository;
use Pantono\Products\Model\Product;
use Pantono\Products\Model\ProductImage;

class ProductsRepository extends MysqlRepository
{
    public function getProductTypeById(int $id): ?array
    {
        return $this->selectSingleRow('product_type', 'id', $id);
    }

    public function getVatRateById(int $id): ?array
    {
        return $this->selectSingleRow('product_vat_rate', 'id', $id);
    }

    public function getStatusById(int $id): ?array
    {
        return $this->selectSingleRow('product_status', 'id', $id);
    }

    public function getImagesForProduct(Product $product): array
    {
        $select = $this->getDb()->select()->from('product_image')
            ->where('deleted=?', 0)
            ->where('product_id=?', $product->getId());

        return $this->getDb()->fetchAll($select);
    }

    public function getProductCategoryById(int $id): ?array
    {
        return $this->selectSingleRow('product_category', 'id', $id);
    }

    public function getProductImageById(int $id): ?array
    {
        return $this->selectSingleRow('product_image', 'id', $id);
    }

    public function getProductById(int $id): ?array
    {
        return $this->selectSingleRow('product', 'id', $id);
    }

    public function getCategoriesForProduct(Product $product): array
    {
        return $this->selectRowsByValues('product_category', ['product_id' => $product->getId()], 'display_order');
    }

    public function saveProduct(Product $product): void
    {
        $id = $this->insertOrUpdateCheck('product', 'id', $product->getId(), $product->getAllData());
        if ($id) {
            $product->setId($id);
        }
        $this->saveImagesForProduct($product);
        $this->saveCategoriesForProduct($product);
    }

    private function saveCategoriesForProduct(Product $product): void
    {
        $doneIds = [];
        foreach ($product->getCategories() as $category) {
            $id = $this->insertOrUpdateCheck('product_image', 'id', $category->getId(), $category->getAllData());
            if ($id) {
                $category->setId($id);
            }
            $doneIds[] = $category->getId();
        }

        $params = [
            'product_id=?' => $product->getId()
        ];
        if (!empty($doneIds)) {
            $params['id NOT IN (?)'] = $doneIds;
        }
        $this->getDb()->delete('product_category', $params);
    }

    private function saveImagesForProduct(Product $product): void
    {
        if (!$product->getId()) {
            throw new \RuntimeException('Product must be saved before saving images');
        }
        $doneIds = [];
        foreach ($product->getImages() as $image) {
            $id = $this->insertOrUpdateCheck('product_image', 'id', $image->getId(), $image->getAllData());
            if ($id) {
                $image->setId($id);
            }
            $doneIds[] = $image->getId();
        }
        $params = [
            'product_id=?' => $product->getId()
        ];
        if (!empty($doneIds)) {
            $params['id NOT IN (?)'] = $doneIds;
        }
        $this->getDb()->delete('product_image', $params);
    }

    public function getRelatedProducts(Product $product): array
    {
        $select = $this->getDb()->select()->from('product_related', [])
            ->joinInner('product', 'product_related.target_product=product.id')
            ->joinInner('product_status', 'product.status_id=product_status.id', [])
            ->where('product_status.archived=?', 0)
            ->where('product_status.visible=?', 1)
            ->where('product_related.source_product=?', $product->getId());

        return $this->getDb()->fetchAll($select);
    }

    public function getCategoryById(int $id): ?array
    {
        return $this->selectSingleRow('category', 'id', $id);
    }
}
