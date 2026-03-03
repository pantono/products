<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\DefaultRepository;
use Pantono\Products\Model\ProductVersion;
use Pantono\Authentication\Model\User;
use Pantono\Products\Filter\ProductHistoryFilter;

class ProductHistoryRepository extends DefaultRepository
{
    public function saveHistoryForVersion(ProductVersion $version, User $user, string $entry): void
    {
        $this->getDb()->insert($this->appendTablePrefix('product_version_history'), [
            'product_version_id' => $version->getId(),
            'user_id' => $user->getId(),
            'date' => (new \DateTime)->format('Y-m-d H:i:s'),
            'entry' => $entry
        ]);
    }

    public function getHistoryByFilter(ProductHistoryFilter $filter): array
    {
        $select = $this->getDb()->select('h.*')->from($this->appendTablePrefix('product_version_history'), 'h')
            ->innerJoin('h', 'product_version', 'v', 'v.id=h.product_version_id');

        if ($filter->getStartDate() !== null) {
            $select->where('h.date >= :start_date')
                ->setParameter('start_date', $filter->getStartDate()->format('Y-m-d H:i:s'));
        }
        if ($filter->getEndDate() !== null) {
            $select->where('h.date <= :end_date')
                ->setParameter('end_date', $filter->getEndDate()->format('Y-m-d H:i:s'));
        }
        if ($filter->getProductVersionId() !== null) {
            $select->where('v.id=:product_version_id')
                ->setParameter('product_version_id', $filter->getProductVersionId());
        }
        if ($filter->getProductId()) {
            $select->where('v.product_id=:product_id')
                ->setParameter('product_id', $filter->getProductId());
        }
        if ($filter->getUserId()) {
            $select->where('h.user_id=:user_id')
                ->setParameter('user_id', $filter->getUserId());
        }
        $this->applyCountAndLimit($select, $filter);
        return $this->getDb()->fetchAll($select);
    }

    public function getEntryById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('product_version_history'), 'id', $id);
    }
}
