<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\DefaultRepository;
use Pantono\Products\Filter\ProductFavouriteFilter;
use Pantono\Database\Query\PantonoQueryBuilder;
use Pantono\Products\Model\Product;
use Pantono\Authentication\Model\User;

class ProductFavouritesRepository extends DefaultRepository
{
    /**
     * @return array<int,mixed>
     */
    public function getFavouritesByFilter(ProductFavouriteFilter $filter): array
    {
        $select = $this->getDb()->select('f.*')->from($this->pt('product_favourite'), 'f');
        $this->applyFilterToSelect($select, $filter);
        $this->applyCountAndLimit($select, $filter);

        return $this->getDb()->fetchAll($select);
    }

    public function getFavouriteCountByFilter(ProductFavouriteFilter $filter): int
    {
        $select = $this->getDb()->select('COUNT(1) as cnt')->from($this->pt('product_favourite'), 'f');
        $this->applyFilterToSelect($select, $filter);

        $row = $this->getDb()->fetchRow($select);
        if (!$row) {
            return 0;
        }
        return (int)$row['cnt'];
    }

    private function applyFilterToSelect(PantonoQueryBuilder $select, ProductFavouriteFilter $filter): void
    {
        if ($filter->getUser() !== null) {
            $select->where('f.user_id = :user_id')
                ->setParameter('user_id', $filter->getUser()->getId());
        }
        if ($filter->getProduct() !== null) {
            $select->where('f.product_id = :product_id')
                ->setParameter('product_id', $filter->getProduct()->getId());
        }

        if ($filter->getDateCreatedStart() !== null) {
            $select->where('f.date_created >= :date_created_start')
                ->setParameter('date_created_start', $filter->getDateCreatedStart());
        }

        if ($filter->getDateCreatedEnd() !== null) {
            $select->where('f.date_created <= :date_created_end')
                ->setParameter('date_created_end', $filter->getDateCreatedEnd());
        }

        if ($filter->getDeleted() !== null) {
            $select->where('f.deleted = :deleted')
                ->setParameter('deleted', $filter->getDeleted() ? 1 : 0);
        }
    }

    public function getFavouriteForUserAndProduct(User $user, Product $product): ?array
    {
        return $this->selectRowByValues($this->pt('product_favourite'), ['product_id' => $product->getId(), 'user_id' => $user->getId()]);
    }
}
