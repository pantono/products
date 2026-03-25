<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\DefaultRepository;
use Pantono\Products\Model\Discount;
use Pantono\Products\Model\DiscountCode;
use Pantono\Products\Filter\SpecialOfferFilter;
use Pantono\Products\Model\ProductVersion;
use Pantono\Products\Model\SpecialOffer;
use Doctrine\DBAL\ArrayParameterType;

class DiscountsRepository extends DefaultRepository
{
    public function getDiscountBaseById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('discount_base'), 'id', $id);
    }

    public function getDiscountById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('discount'), 'id', $id);
    }

    public function getDiscountCodeById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('discount_code'), 'id', $id);
    }

    public function getDiscountCodeByCode(string $code): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('discount_code'), 'code', $code);
    }

    public function getRulesForDiscount(Discount $discount): array
    {
        return $this->selectRowsByValues($this->appendTablePrefix('discount_rule'), ['discount_id' => $discount->getId()]);
    }

    public function saveDiscount(Discount $discount): void
    {
        $id = $this->insertOrUpdateCheck($this->appendTablePrefix('discount'), 'id', $discount->getId(), $discount->getAllData());
        if ($id) {
            $discount->setId($id);
        }

        $deleteQb = $this->getDb()->createQueryBuilder()->delete($this->appendTablePrefix('discount_rule'))
            ->andWhere('discount_id=:discount_id')
            ->setParameter('discount_id', $discount->getId());
        $ids = [];
        foreach ($discount->getRules() as $rule) {
            $id = $this->insertOrUpdate($this->appendTablePrefix('discount_rule'), 'id', $rule->getId(), [
                'discount_id' => $discount->getId(),
                'field' => $rule->getField(),
                'value' => $rule->getValue(),
                'operand' => $rule->getOperand(),
                'include' => $rule->isInclude() ? 1 : 0
            ]);
            if ($id) {
                $rule->setId($id);
            }
            $ids[] = $rule->getId();
        }
        if (count($ids) > 0) {
            $deleteQb->andWhere('id not in (:ids)')
                ->setParameter('ids', $ids, ArrayParameterType::INTEGER);
        }
        $deleteQb->executeQuery();
    }

    public function saveDiscountCode(DiscountCode $code): void
    {
        $id = $this->insertOrUpdateCheck($this->appendTablePrefix('discount_code'), 'id', $code->getId(), $code->getAllData());
        if ($id) {
            $code->setId($id);
        }
    }

    public function logDiscountCodeUsed(Discount $discount, int $orderId): void
    {
        $this->insert($this->appendTablePrefix('discount_code_usage'), [
            'discount_id' => $discount->getId(),
            'order_id' => $orderId,
            'date_used' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    public function getSpecialOfferById(int $id): ?array
    {
        return $this->selectSingleRow($this->appendTablePrefix('special_offer'), 'id', $id);
    }

    public function getOffersByFilter(SpecialOfferFilter $filter): array
    {
        $select = $this->getDb()->select('s.*')->from($this->appendTablePrefix('special_offer'), 's');

        if ($filter->getDiscount() !== null) {
            $select->where('s.discount_id=:discount_id')
                ->setParameter('discount_id', $filter->getDiscount()->getId());
        }
        if ($filter->getActive() !== null) {
            $select->where('s.active=:active')
                ->setParameter('active', $filter->getActive() ? 1 : 0);
        }
        if ($filter->getStartDate() !== null) {
            $select->where('(s.start_date <= :start_date and s.end_date >= :start_date)')
                ->setParameter('start_date', $filter->getStartDate()->format('Y-m-d H:i:s'));
        }
        if ($filter->getEndDate() !== null) {
            $select->where('(s.start_date <= :end_date and s.end_date >= :end_date)')
                ->setParameter('end_date', $filter->getEndDate()->format('Y-m-d H:i:s'));
        }

        $this->applyCountAndLimit($select, $filter);
        return $this->getDb()->fetchAll($select);
    }

    public function getOffersForProductVersion(ProductVersion $version): array
    {
        $select = $this->getDb()->select('s.*')->from($this->appendTablePrefix('special_offer_product'), 'p')
            ->innerJoin('p', 'special_offer', 's', 's.id=p.special_offer_id')
            ->where('p.product_version_id=:product_version_id')
            ->setParameter('product_version_id', $version->getId());

        return $this->getDb()->fetchAll($select);
    }

    public function clearProductsForOffer(SpecialOffer $offer): void
    {
        $this->getDb()->delete($this->appendTablePrefix('special_offer_product'), ['special_offer_id' => $offer->getId()]);
    }

    public function addProductToOffer(ProductVersion $version, SpecialOffer $offer): void
    {
        $this->insertIgnore($this->appendTablePrefix('special_offer_product'), [
            'product_version_id' => $version->getId(),
            'special_offer_id' => $offer->getId()
        ]);
    }

    public function saveSpecialOffer(SpecialOffer $offer): void
    {
        $id = $this->insertOrUpdate($this->appendTablePrefix('special_offer'), 'id', $offer->getId(), $offer->getAllData());
        if ($id) {
            $offer->setId($id);
        }
    }
}
