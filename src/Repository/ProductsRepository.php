<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\MysqlRepository;
use Pantono\Products\Model\ProductVersion;
use Pantono\Products\Filter\ProductFilter;
use Pantono\Products\Model\Product;
use Pantono\Products\Model\ProductBrand;

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

    public function getImagesForProduct(ProductVersion $product): array
    {
        $select = $this->getDb()->select()->from('product_image')
            ->where('deleted=?', 0)
            ->where('version_id=?', $product->getId());

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

    public function getProductVersionById(int $id): ?array
    {
        return $this->selectSingleRow('product_version', 'id', $id);
    }

    public function getProductById(int $id): ?array
    {
        return $this->selectSingleRow('product', 'id', $id);
    }

    public function getCategoriesForProduct(ProductVersion $version): array
    {
        return $this->selectRowsByValues('product_category', ['version_id' => $version->getId()], 'display_order');
    }

    public function saveProductVersion(ProductVersion $product): void
    {
        $id = $this->insertOrUpdateCheck('product_version', 'id', $product->getId(), $product->getAllData());
        if ($id) {
            $product->setId($id);
        }
        $this->saveImagesForProduct($product);
        $this->saveCategoriesForProduct($product);
    }

    public function saveProduct(Product $product): void
    {
        $id = $this->insertOrUpdateCheck('product', 'id', $product->getId(), $product->getAllData());
        if ($id) {
            $product->setId($id);
        }
    }

    private function saveCategoriesForProduct(ProductVersion $version): void
    {
        $doneIds = [];
        foreach ($version->getCategories() as $category) {
            $category->setVersionId($version->getId());
            $id = $this->insertOrUpdateCheck('product_category', 'id', $category->getId(), $category->getAllData());
            if ($id) {
                $category->setId($id);
            }
            $doneIds[] = $category->getId();
        }

        $params = [
            'version_id=?' => $version->getId()
        ];
        if (!empty($doneIds)) {
            $params['id NOT IN (?)'] = $doneIds;
        }
        $this->getDb()->delete('product_category', $params);
    }

    private function saveImagesForProduct(ProductVersion $product): void
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
            'version_id=?' => $product->getId()
        ];
        if (!empty($doneIds)) {
            $params['id NOT IN (?)'] = $doneIds;
        }
        $this->getDb()->delete('product_image', $params);
    }

    public function getRelatedProducts(ProductVersion $product): array
    {
        $select = $this->getDb()->select()->from('product_related', [])
            ->joinInner('product_version', 'product_related.target_product=product_version.id')
            ->joinInner('product_status', 'product_version.status_id=product_status.id', [])
            ->where('product_status.archived=?', 0)
            ->where('product_status.visible=?', 1)
            ->where('product_related.source_product=?', $product->getId());

        return $this->getDb()->fetchAll($select);
    }

    public function getProductBySlug(string $slug): ?array
    {
        return $this->selectSingleRow('product', 'slug', $slug);
    }

    public function getProductsByFilter(ProductFilter $filter): array
    {
        $select = $this->getDb()->select()->from('product');
        if ($filter->getSearch() !== null) {
            $select->where('(product.name like ?', '%' . $filter->getSearch() . '%')
                ->orWhere('product.description like ?)', '%' . $filter->getSearch() . '%');
        }
        if ($filter->getCategory() !== null) {
            $select->joinInner('product_category', 'product_category.version_id=product.published_draft_id', [])
                ->where('product_category.category_id=?', $filter->getCategory()->getId());
        }
        if ($filter->getStatus() !== null) {
            $select->where('product.status_id=?', $filter->getStatus()->getId());
        }
        foreach ($filter->getColumns() as $column) {
            $select->where($column['name'] . $column['operator'] . $column['placeholder'], $column['value']);
        }
        $filter->setTotalResults($this->getCount($select));
        $select->limitPage($filter->getPage(), $filter->getPerPage());
        return $this->getDb()->fetchAll($select);
    }

    public function getFlagsForProduct(ProductVersion $version): array
    {
        $select = $this->getDb()->select()->from('product_flag', [])
            ->joinInner('flag', 'flag.id=product_flag.flag_id')
            ->where('product_flag.version_id=?', $version->getId());

        return $this->getDb()->fetchAll($select);
    }

    public function getFlagById(int $id): ?array
    {
        return $this->selectSingleRow('flag', 'id', $id);
    }

    public function getBrandById(int $id): ?array
    {
        return $this->selectSingleRow('product_brand', 'id', $id);
    }

    public function getAllBrands(): array
    {
        return $this->selectAll('product_brand');
    }

    public function getAllFlags(): array
    {
        return $this->selectAll('flag');
    }

    public function getConditionById(int $id): ?array
    {
        return $this->selectSingleRow('product_condition', 'id', $id);
    }

    public function getAllConditions(): array
    {
        return $this->selectAll('product_condition', 'name');
    }

    public function getAllVatRates(): array
    {
        return $this->selectAll('product_vat_rate', 'rate ASC');
    }

    public function saveBrand(ProductBrand $brand): void
    {
        $id = $this->insertOrUpdateCheck('product_brand', 'id', $brand->getId(), ['id' => $brand->getId(), 'name' => $brand->getName()]);
        if ($id) {
            $brand->setId($id);
        }
    }
}
