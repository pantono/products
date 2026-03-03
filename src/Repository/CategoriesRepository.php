<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\DefaultRepository;
use Pantono\Products\Model\Category;
use Pantono\Products\Filter\CategoryFilter;
use Pantono\Products\Model\CategoryFieldType;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Query\QueryBuilder;

class CategoriesRepository extends DefaultRepository
{
    public function getCategoryById(int $id): ?array
    {
        return $this->getDb()->fetchRow($this->getCategoryBaseSelect()->where('category.id=:id')->setParameter('id', $id));
    }

    public function getCategoryBySlug(string $slug): ?array
    {
        return $this->getDb()->fetchRow($this->getCategoryBaseSelect()->where('category.slug=?', $slug));
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
        $select = $this->getCategoryBaseSelect();

        if ($filter->getSearch() !== null) {
            $select->where('(c.title like :search or c.description like :search)')
                ->setParameter('search', '%' . $filter->getSearch() . '%');
        }
        if ($filter->getParentId() === 0) {
            $select->where('c.parent_id IS NULL');
        } elseif ($filter->getParentId() !== null) {
            $select->where('c.parent_id=:parent_id')
                ->setParameter('parent_id', $filter->getParentId());
        }
        if ($filter->getSlug() !== null) {
            $select->where('c.slug=:slug')
                ->setParameter('slug', $filter->getSlug());
        }
        $paramIndex = 0;
        foreach ($filter->getColumns() as $column) {
            $placeholder = ':field_' . $paramIndex;
            if ($column['operator'] === 'IN' || $column['operator'] === 'NOT IN') {
                $select->where($column['name'] . $column['operator'] . '(' . $placeholder . ')')
                    ->setParameter($placeholder, $column['value'], ArrayParameterType::STRING);
            } else {
                $select->where($column['name'] . $column['operator'] . $placeholder)
                    ->setParameter($placeholder, $column['value']);
            }
            $paramIndex++;
        }
        $this->applyCountAndLimit($select, $filter);
        $select->addOrderBy($filter->getOrderBy());
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

    private function getCategoryBaseSelect(): QueryBuilder
    {
        return $this->getDb()->select('c.*', 'CONCAT_WS(\' -> \', parent_parent_parent.title, parent_parent.title, parent.title) as breadcrumb')->from('category', 'c')
            ->leftJoin('c', 'category', 'parent', 'parent.id=c.parent_id')
            ->leftJoin('parent', 'category', 'parent_parent', 'parent_parent.id=parent.parent_id')
            ->leftJoin('parent_parent', 'category', 'parent_parent_parent', 'parent_parent_parent.id=parent_parent.parent_id');
    }
}
