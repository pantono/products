<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\Discount;

abstract class AbstractDiscountSaveEvent extends Event
{
    private Discount $current;
    private ?Discount $previous = null;

    public function getCurrent(): Discount
    {
        return $this->current;
    }

    public function setCurrent(Discount $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?Discount
    {
        return $this->previous;
    }

    public function setPrevious(?Discount $previous): void
    {
        $this->previous = $previous;
    }
}
