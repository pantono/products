<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\MysqlRepository;

class CategoriesRepository extends MysqlRepository
{
    public function getCategoryById(int $id): ?array
    {
        return $this->selectSingleRow('category', 'id', $id);
    }
}
