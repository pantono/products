<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\ProductBrand;

class AbstractBrandSaveEvent extends Event
{
    private ProductBrand $current;
    private ?ProductBrand $previous = null;

    public function getCurrent(): ProductBrand
    {
        return $this->current;
    }

    public function setCurrent(ProductBrand $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?ProductBrand
    {
        return $this->previous;
    }

    public function setPrevious(?ProductBrand $previous): void
    {
        $this->previous = $previous;
    }
}
