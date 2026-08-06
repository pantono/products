<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\DefaultRepository;
use Pantono\Products\Filter\ReviewFilter;

class ProductReviewsRepository extends DefaultRepository
{
    public function getReviewsByFilter(ReviewFilter $filter): array
    {
        $select = $this->getDb()->select('r.*')->from('review', 'r')
            ->orderBy($filter->getOrderColumn(), $filter->getOrderDirection());
        
        if ($filter->getProduct() !== null) {
            $select->andWhere('r.product_id = :product_id')
                ->setParameter('product_id', $filter->getProduct()->getId());
        }
        if ($filter->getApproved() !== null) {
            $select->andWhere('r.approved = :approved')
                ->setParameter('approved', $filter->getApproved() ? 1 : 0);
        }

        if ($filter->getDateCreatedStart() !== null) {
            $select->andWhere('r.date_created >= :date_created_start')
                ->setParameter('date_created_start', $filter->getDateCreatedStart()->format('Y-m-d H:i:s'));
        }

        if ($filter->getDateCreatedEnd() !== null) {
            $select->andWhere('r.date_created <= :date_created_end')
                ->setParameter('date_created_end', $filter->getDateCreatedEnd()->format('Y-m-d H:i:s'));
        }

        if ($filter->getUser() !== null) {
            $select->andWhere('r.user_id = :user_id')
                ->setParameter('user_id', $filter->getUser()->getId());
        }

        $this->applyCountAndLimit($select, $filter);
        return $this->getDb()->fetchAll($select);
    }
}
