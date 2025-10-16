<?php

namespace Pantono\Products\Model;

class ProductPrice
{
    private ProductVersion $product;

    public function __construct(ProductVersion $product)
    {
        $this->product = $product;
    }

    public function getBasePrice(): float
    {
        return $this->product->getPrice();
    }

    public function getVat(): float
    {
        return ($this->getPrice() / 100) * $this->product->getVatRate()->getRate();
    }

    public function getDiscount(): float
    {
        $discount = 0;
        foreach ($this->product->getActiveOffers() as $offer) {
            if ($offer->getDiscount()?->getBase()->isPercentage()) {
                $discount += ($this->getBasePrice() / 100) * $offer->getDiscount()->getAmount();
            }
            if ($offer->getDiscount()?->getBase()->isAmount()) {
                $discount += $offer->getDiscount()->getAmount();
            }
        }
        return $discount;
    }

    public function getPrice(): float
    {
        return $this->getBasePrice() - $this->getDiscount();
    }

    public function getPriceIncVat(): float
    {
        return $this->getPrice() + $this->getVat();
    }

    public function getPricePerItem(): float
    {
        return $this->getPrice() / $this->product->getItemsIncluded();
    }

    public function getPricePerItemIncVat(): float
    {
        return $this->getPriceIncVat() / $this->product->getItemsIncluded();
    }

    public function getRrp(): float
    {
        return $this->product->getRrp();
    }
}
