<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\MysqlRepository;
use Pantono\Products\Model\ProductVersion;
use Pantono\Products\Model\ProductStatType;

class ProductStatsRepository extends MysqlRepository
{
    public function logStatForProduct(ProductVersion $version, ProductStatType $type, ?int $userId = null, \DateTimeInterface $date = new \DateTimeImmutable()): void
    {
        $this->getDb()->insert('product_stat', [
            'date' => $date->format('Y-m-d H:i:s'),
            'type_id' => $type->getId(),
            'product_version_id' => $version->getId(),
            'user_id' => $userId,
        ]);
    }

    public function getStatTypById(int $id): ?array
    {
        return $this->selectSingleRow('product_stat_type', 'id', $id);
    }

    public function groupStats(ProductStatType $type, \DateTimeInterface $date): void
    {
        $this->getDb()->delete('product_stat_grouped', ['type_id' => $type->getId(), 'date' => $date->format('Y-m-d')]);
        $this->getDb()->query('INSERT into product_stat_grouped (SELECT date(`date`), :type_id, product_version_id, product_id, COUNT(1) as cnt from product_stat where date(`date`)=:date and type_id=:type_id group by product_version_id)', ['type_id' => $type->getId(), 'date' => $date->format('Y-m-d')]);
    }
}
