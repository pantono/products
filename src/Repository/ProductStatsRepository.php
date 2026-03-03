<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\DefaultRepository;
use Pantono\Products\Model\ProductVersion;
use Pantono\Products\Model\ProductStatType;

class ProductStatsRepository extends DefaultRepository
{
    public function logStatForProduct(ProductVersion $version, ProductStatType $type, ?int $userId = null, \DateTimeInterface $date = new \DateTimeImmutable()): void
    {
        $this->getDb()->insert($this->appendTablePrefix('product_stat'), [
            'date' => $date->format('Y-m-d H:i:s'),
            'type_id' => $type->getId(),
            'product_version_id' => $version->getId(),
            'user_id' => $userId,
        ]);
    }

    public function getStatTypById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_stat_type'), 'id', $id);
    }

    public function groupStats(ProductStatType $type, \DateTimeInterface $date): void
    {
        $this->getDb()->delete('product_stat_grouped', ['type_id' => $type->getId(), 'date' => $date->format('Y-m-d')]);
        $query = $this->getDb()->select(
            'DATE(ps.date) AS date',
            ':type_id AS type_id',
            'ps.product_version_id',
            'ps.product_id',
            'COUNT(1) AS cnt'
        )
            ->from('product_stat', 'ps')
            ->where('DATE(ps.date) = :date')
            ->andWhere('ps.type_id = :type_id')
            ->groupBy('ps.product_version_id');
        $this->getDb()->query('INSERT into product_stat_grouped (' . $query->getSQL() . ')', ['type_id' => $type->getId(), 'date' => $date->format('Y-m-d')]);
    }
}
