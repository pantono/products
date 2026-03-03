<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\DefaultRepository;
use Pantono\Products\Model\ProductVersion;
use Pantono\Products\Filter\ProductFilter;
use Pantono\Products\Model\Product;
use Pantono\Products\Model\ProductBrand;
use Pantono\Products\Model\ProductFieldType;
use Doctrine\DBAL\ArrayParameterType;

class ProductsRepository extends DefaultRepository
{
    public function getProductTypeById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_type'), 'id', $id);
    }

    public function getVatRateById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_vat_rate'), 'id', $id);
    }

    public function getStatusById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_status'), 'id', $id);
    }

    public function getImagesForProduct(ProductVersion $product): array
    {
        $select = $this->getDb()->select('i.*')->from('product_image', 'i')
            ->where('i.deleted=0')
            ->where('i.version_id=:version_id')
            ->setParameter('version_id', $product->getId());

        return $this->getDb()->fetchAll($select);
    }

    public function getProductCategoryById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_category'), 'id', $id);
    }

    public function getProductImageById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_image'), 'id', $id);
    }

    public function getProductVersionById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_version'), 'id', $id);
    }

    public function getProductById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product'), 'id', $id);
    }

    public function getCategoriesForProduct(ProductVersion $version): array
    {
        return $this->selectRowsByValues($this->appendTablePrefix('product_category'), ['version_id' => $version->getId()], 'display_order');
    }

    public function saveProductVersion(ProductVersion $productVersion): void
    {
        $id = $this->insertOrUpdateCheck($this->appendTablePrefix('product_version'), 'id', $productVersion->getId(), $productVersion->getAllData());
        if ($id) {
            $productVersion->setId($id);
        }
        $this->saveImagesForProduct($productVersion);
        $this->saveCategoriesForProduct($productVersion);
        $this->saveFieldsForProduct($productVersion);
    }

    public function saveProduct(Product $product): void
    {
        $id = $this->insertOrUpdateCheck($this->appendTablePrefix('product'), 'id', $product->getId(), $product->getAllData());
        if ($id) {
            $product->setId($id);
        }
    }

    private function saveCategoriesForProduct(ProductVersion $version): void
    {
        $doneIds = [];
        foreach ($version->getCategories() as $category) {
            $category->setVersionId($version->getId());
            $id = $this->insertOrUpdateCheck($this->appendTablePrefix('product_category'), 'id', $category->getId(), $category->getAllData());
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
        $this->getDb()->delete($this->appendTablePrefix('product_category'), $params);
    }

    private function saveFieldsForProduct(ProductVersion $version): void
    {
        $this->getDb()->delete($this->appendTablePrefix('product_field'), ['product_version_id=?' => $version->getId()]);
        foreach ($version->getFields() as $field) {
            if ($field->getType()) {
                $this->insert($this->appendTablePrefix('product_field'), ['product_version_id' => $version->getId(), 'type_id' => $field->getType()->getId(), 'value' => $field->getValue()]);
            }
        }
    }

    private function saveImagesForProduct(ProductVersion $product): void
    {
        if (!$product->getId()) {
            throw new \RuntimeException('Product must be saved before saving images');
        }
        $this->getDb()->delete($this->appendTablePrefix('product_image'), ['version_id=?' => $product->getId()]);
        foreach ($product->getImages() as $image) {
            $image->setVersionId($product->getId());
            $this->getDb()->insert($this->appendTablePrefix('product_image'), $image->getAllData());
        }
    }

    public function getRelatedProducts(ProductVersion $productVersion): array
    {
        $select = $this->getDb()->select('v.*')->from($this->appendTablePrefix('product_related'), 'r')
            ->innerJoin('r', $this->appendTablePrefix('product_version'), 'v', 'v.id=r.target_product')
            ->innerJoin('v', $this->appendTablePrefix('product_status'), 's', 's.id=v.status_id')
            ->where('s.archived=0')
            ->where('s.visible=1')
            ->where('s.source_product=:product_id')
            ->setParameter('product_id', $productVersion->getId());

        return $this->getDb()->fetchAll($select);
    }

    public function getProductBySlug(string $slug): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product'), 'slug', $slug);
    }

    public function getProductsByFilter(ProductFilter $filter): array
    {
        $select = $this->getDb()->select('p.*')->from('product', 'p')
            ->leftJoin('p', 'product_version', 'published', 'published.id=p.published_draft_id');

        if ($filter->getOrderBy()) {
            $select->addOrderBy($filter->getOrderBy());
        }

        if ($filter->getSearch() !== null) {
            $select->where('(published.title like :search or product.code like :search or published.description like :search)')
                ->setParameter('search', '%' . $filter->getSearch() . '%');
        }
        if (!empty($filter->getCategoryIds())) {
            $select->innerJoin('published', 'product_category', 'c', 'c.version_id=published.id')
                ->where('c.category_id in (:categories)')
                ->setParameter('categories', $filter->getCategoryIds(), ArrayParameterType::INTEGER);
        }
        if ($filter->getStatus() !== null) {
            $select->where('published.status_id=?')
                ->setParameter('status', $filter->getStatus()->getId());
        }
        $paramIndex = 0;
        foreach ($filter->getColumns() as $column) {
            $operator = $column['operator'];
            $placeHolder = 'param_' . $paramIndex;
            $value = $column['value'];
            if (($operator === 'IN' || $operator === 'NOT IN') && is_string($value)) {
                $select->where($column['name'] . ' ' . $operator . ' (:' . $placeHolder . ')')
                    ->setParameter($placeHolder, $value, ArrayParameterType::STRING);
            } else {
                $select->where($column['name'] . ' ' . $operator . ' :' . $placeHolder)
                    ->setParameter($placeHolder, $value);
            }
            $paramIndex++;
        }

        $this->applyCountAndLimit($select, $filter);

        return $this->getDb()->fetchAll($select);
    }

    public function getFlagsForProductVersion(ProductVersion $version): array
    {
        $select = $this->getDb()->select('f.*')->from($this->appendTablePrefix('product_flag'), 'pf')
            ->innerJoin('f', $this->appendTablePrefix('flag'), 'f', 'pf.flat_id=f.id')
            ->where('pf.version_id=:version_id')
            ->setParameter('version_id', $version->getId());

        return $this->getDb()->fetchAll($select);
    }

    public function getFlagById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('flag'), 'id', $id);
    }

    public function getBrandById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_brand'), 'id', $id);
    }

    public function getAllBrands(): array
    {
        return $this->selectAll($this->appendTablePrefix('product_brand'));
    }

    public function getAllFlags(): array
    {
        return $this->selectAll($this->appendTablePrefix('flag'));
    }

    public function getConditionById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_condition'), 'id', $id);
    }

    public function getAllConditions(): array
    {
        return $this->selectAll($this->appendTablePrefix('product_condition'), 'name');
    }

    public function getAllVatRates(): array
    {
        return $this->selectAll($this->appendTablePrefix('product_vat_rate'), 'rate ASC');
    }

    public function saveBrand(ProductBrand $brand): void
    {
        $id = $this->insertOrUpdateCheck($this->appendTablePrefix('product_brand'), 'id', $brand->getId(), ['name' => $brand->getName()]);
        if ($id) {
            $brand->setId($id);
        }
    }

    public function getFieldTypeById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_field_type'), 'id', $id);
    }

    public function getFieldsForProductVersion(ProductVersion $productVersion): array
    {
        return $this->selectRowsByValues($this->appendTablePrefix('product_field'), ['product_version_id' => $productVersion->getId()]);
    }

    public function saveProductFieldType(ProductFieldType $type): void
    {
        $id = $this->insertOrUpdate($this->appendTablePrefix('product_field_type'), 'id', $type->getId(), $type->getAllData());
        if ($id) {
            $type->setId($id);
        }
    }

    public function getAllStatuses(): array
    {
        return $this->selectAll($this->appendTablePrefix('product_status'));
    }
}
