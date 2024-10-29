<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\Product;

abstract class AbstractProductSaveEvent extends Event
{
    private Product $current;
    private ?Product $previous;

    public function getCurrent(): Product
    {
        return $this->current;
    }

    public function setCurrent(Product $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?Product
    {
        return $this->previous;
    }

    public function setPrevious(?Product $previous): void
    {
        $this->previous = $previous;
    }
}
