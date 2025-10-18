<?php

namespace Pantono\Products\Repository;

use Pantono\Database\Repository\MysqlRepository;
use Pantono\Products\Model\Discount;
use Pantono\Products\Model\DiscountCode;
use Pantono\Products\Filter\SpecialOfferFilter;
use Pantono\Products\Model\ProductVersion;
use Pantono\Products\Model\SpecialOffer;

class DiscountsRepository extends MysqlRepository
{
    public function getDiscountBaseById(int $id): ?array
    {
        return $this->selectSingleRow('discount_base', 'id', $id);
    }

    public function getDiscountById(int $id): ?array
    {
        return $this->selectSingleRow('discount', 'id', $id);
    }

    public function getDiscountCodeById(int $id): ?array
    {
        return $this->selectSingleRow('discount_code', 'id', $id);
    }

    public function getDiscountCodeByCode(string $code): ?array
    {
        return $this->selectSingleRow('discount_code', 'code', $code);
    }

    public function getRulesForDiscount(Discount $discount): array
    {
        return $this->selectRowsByValues('discount_rule', ['discount_id' => $discount->getId()]);
    }

    public function saveDiscount(Discount $discount): void
    {
        $id = $this->insertOrUpdateCheck('discount', 'id', $discount->getId(), $discount->getAllData());
        if ($id) {
            $discount->setId($id);
        }

        $params = [
            'discount_id=?' => $discount->getId()
        ];
        $ids = [];
        foreach ($discount->getRules() as $rule) {
            $id = $this->insertOrUpdate('discount_rule', 'id', $rule->getId(), [
                'discount_id' => $discount->getId(),
                'field' => $rule->getField(),
                'value' => $rule->getValue(),
                'operand' => $rule->getOperand(),
                'include' => $rule->isInclude()
            ]);
            if ($id) {
                $rule->setId($id);
            }
            $ids[] = $rule->getId();
        }
        if (count($ids) > 0) {
            $params['id NOT IN (?)'] = $ids;
        }
        $this->getDb()->delete('discount_rule', $params);
    }

    public function saveDiscountCode(DiscountCode $code): void
    {
        $id = $this->insertOrUpdateCheck('discount_code', 'id', $code->getId(), $code->getAllData());
        if ($id) {
            $code->setId($id);
        }
    }

    public function logDiscountCodeUsed(Discount $discount, int $orderId): void
    {
        $this->insert('discount_code_usage', [
            'discount_id' => $discount->getId(),
            'order_id' => $orderId,
            'date_used' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    public function getSpecialOfferById(int $id): ?array
    {
        return $this->selectSingleRow('special_offer', 'id', $id);
    }

    public function getOffersByFilter(SpecialOfferFilter $filter): array
    {
        $select = $this->getDb()->select()->from('special_offer');

        if ($filter->getDiscount() !== null) {
            $select->where('special_offer.discount_id=?', $filter->getDiscount()->getId());
        }
        if ($filter->getActive() !== null) {
            $select->where('active=?', $filter->getActive() ? 1 : 0);
        }
        if ($filter->getStartDate() !== null) {
            $select->where('(start_date <= ?', $filter->getStartDate()->format('Y-m-d H:i:s'))
                ->where('end_date >= ?)', $filter->getStartDate()->format('Y-m-d H:i:s'));
        }
        if ($filter->getEndDate() !== null) {
            $select->where('(start_date <= ?', $filter->getEndDate()->format('Y-m-d H:i:s'))
                ->where('end_date >= ?)', $filter->getEndDate()->format('Y-m-d H:i:s'));
        }

        $filter->setTotalResults($this->getCount($select));
        $select->limitPage($filter->getPage(), $filter->getPerPage());

        return $this->getDb()->fetchAll($select);
    }

    public function getOffersForProductVersion(ProductVersion $version): array
    {
        $select = $this->getDb()->select()->from('special_offer_product', [])
            ->join('special_offer', 'special_offer.id=special_offer_product.special_offer_id')
            ->where('special_offer_product.product_version_id=?', $version->getId());

        return $this->getDb()->fetchAll($select);
    }

    public function clearProductsForOffer(SpecialOffer $offer): void
    {
        $this->getDb()->delete('special_offer_product', ['special_offer_id' => $offer->getId()]);
    }

    public function addProductToOffer(ProductVersion $version, SpecialOffer $offer): void
    {
        $this->insertIgnore('special_offer_product', [
            'product_version_id' => $version->getId(),
            'special_offer_id' => $offer->getId()
        ]);
    }

    public function saveSpecialOffer(SpecialOffer $offer): void
    {
        $id = $this->insertOrUpdate('special_offer', 'id', $offer->getId(), $offer->getAllData());
        if ($id) {
            $offer->setId($id);
        }
    }
}
