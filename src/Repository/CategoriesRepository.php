<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\MysqlRepository;
use Pantono\Products\Model\Category;
use Pantono\Products\Filter\CategoryFilter;

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
    }


    public function getCategoriesByFilter(CategoryFilter $filter): array
    {
        $select = $this->getDb()->select()->from('category');

        if ($filter->getSearch() !== null) {
            $select->where('(name like ?', '%' . $filter->getSearch() . '%')
                ->orWhere('description like ?)', '%' . $filter->getSearch() . '%');
        }
        if ($filter->getSlug() !== null) {
            $select->where('slug=?', $filter->getSlug());
        }
        foreach ($filter->getColumns() as $column) {
            $select->where($column['name'] . $column['operator'] . $column['placeholder'], $column['value']);
        }
        $filter->setTotalResults($this->getCount($select));
        $select->limitPage($filter->getPage(), $filter->getPerPage());
        return $this->getDb()->fetchAll($select);
    }

    public function getStatusById(int $id): ?array
    {
        return $this->selectSingleRow('category_status', 'id', $id);
    }
}
