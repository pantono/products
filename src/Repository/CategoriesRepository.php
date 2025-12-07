<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\MysqlRepository;
use Pantono\Products\Model\Category;
use Pantono\Products\Filter\CategoryFilter;
use Pantono\Products\Model\CategoryFieldType;

class CategoriesRepository extends MysqlRepository
{
    public function getCategoryById(int $id): ?array
    {
        return $this->selectSingleRow('category', 'id', $id);
    }

    public function getCategoryBySlug(string $slug): ?array
    {
        return $this->selectSingleRow('category', 'slug', $slug);
    }

    public function saveCategory(Category $category): void
    {
        $id = $this->insertOrUpdateCheck('category', 'id', $category->getId(), $category->getAllData());
        if ($id) {
            $category->setId($id);
        }
        $this->getDb()->delete('category_field', ['category_id=?' => $category->getId()]);
        foreach ($category->getFields() as $field) {
            $this->insert('category_field', ['category_id' => $category->getId(), 'type_id' => $field->getType()->getId(), 'value' => $field->getValue()]);
        }
    }


    public function getCategoriesByFilter(CategoryFilter $filter): array
    {
        $select = $this->getDb()->select()->from('category')
            ->joinLeft(['parent' => 'category'], 'parent.id=category.parent_id', [])
            ->joinLeft(['parent_parent' => 'category'], 'parent_parent.id=parent.parent_id', [])
            ->joinLeft(['parent_parent_parent' => 'category'], 'parent_parent.parent_id=parent_parent_parent.id', ['CONCAT_WS(\' -> \', parent_parent_parent.title, parent_parent.title, parent.title)']);

        if ($filter->getSearch() !== null) {
            $select->where('(category.title like ?', '%' . $filter->getSearch() . '%')
                ->orWhere('category.description like ?)', '%' . $filter->getSearch() . '%');
        }
        if ($filter->getParentId() === 0) {
            $select->where('category.parent_id IS NULL');
        } elseif ($filter->getParentId() !== null) {
            $select->where('category.parent_id=?', $filter->getParentId());
        }
        if ($filter->getSlug() !== null) {
            $select->where('category.slug=?', $filter->getSlug());
        }
        foreach ($filter->getColumns() as $column) {
            $select->where($column['name'] . $column['operator'] . $column['placeholder'], $column['value']);
        }
        $filter->setTotalResults($this->getCount($select));
        $select->limitPage($filter->getPage(), $filter->getPerPage());
        $select->order($filter->getOrderBy());
        return $this->getDb()->fetchAll($select);
    }

    public function getStatusById(int $id): ?array
    {
        return $this->selectSingleRow('category_status', 'id', $id);
    }

    public function getFieldTypeById(int $id): ?array
    {
        return $this->selectSingleRow('category_field_type', 'id', $id);
    }

    public function getFieldTypeByName(string $name): ?array
    {
        return $this->selectSingleRow('category_field_type', 'name', $name);
    }

    public function saveCategoryFieldType(CategoryFieldType $type): void
    {
        $id = $this->insertOrUpdate('category_field_type', 'id', $type->getId(), $type->getAllData());
        if ($id) {
            $type->setId($id);
        }
    }

    public function getFieldsForCategory(Category $category): array
    {
        return $this->selectRowsByValues('category_field', ['category_id' => $category->getId()]);
    }

    public function getChildren(int $id): array
    {
        return $this->selectRowsByValues('category', ['parent_id' => $id]);
    }
}
