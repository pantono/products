<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\DiscountCode;

abstract class AbstractDiscountCodeSaveEvent extends Event
{
    private DiscountCode $current;
    private ?DiscountCode $previous = null;

    public function getCurrent(): DiscountCode
    {
        return $this->current;
    }

    public function setCurrent(DiscountCode $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?DiscountCode
    {
        return $this->previous;
    }

    public function setPrevious(?DiscountCode $previous): void
    {
        $this->previous = $previous;
    }
}
