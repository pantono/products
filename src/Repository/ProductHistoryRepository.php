<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\MysqlRepository;
use Pantono\Products\Model\ProductVersion;
use Pantono\Authentication\Model\User;
use Pantono\Products\Filter\ProductHistoryFilter;

class ProductHistoryRepository extends MysqlRepository
{
    public function saveHistoryForVersion(ProductVersion $version, User $user, string $entry): void
    {
        $this->getDb()->insert('product_version_history', [
            'product_version_id' => $version->getId(),
            'user_id' => $user->getId(),
            'date' => (new \DateTime)->format('Y-m-d H:i:s'),
            'entry' => $entry
        ]);
    }

    public function getHistoryByFilter(ProductHistoryFilter $filter): array
    {
        $select = $this->getDb()->select()->from('product_version_history')
            ->joinInner('product_version', 'product_version_history.product_version_id=product_version.id', []);
        if ($filter->getStartDate() !== null) {
            $select->where('product_version_history.date >= ?', $filter->getStartDate()->format('Y-m-d H:i:s'));
        }
        if ($filter->getEndDate() !== null) {
            $select->where('product_version_history.date <= ?', $filter->getEndDate()->format('Y-m-d H:i:s'));
        }
        if ($filter->getProductVersionId() !== null) {
            $select->where('product_version.id=?', $filter->getProductVersionId());
        }
        if ($filter->getProductId()) {
            $select->where('product_version.product_id=?', $filter->getProductId());
        }
        if ($filter->getUserId()) {
            $select->where('product_version_history.user_id=?', $filter->getUserId());
        }
        $filter->setTotalResults($this->getCount($select));
        $select->limitPage($filter->getPage(), $filter->getPerPage());
        return $this->getDb()->fetchAll($select);
    }

    public function getEntryById(int $id):?array
    {
        return $this->selectSingleRow('product_version_history', 'id', $id);
    }
}
