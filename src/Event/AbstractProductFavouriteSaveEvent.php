<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\ProductFavourite;

abstract class AbstractProductFavouriteSaveEvent extends Event
{
    private ProductFavourite $current;
    private ?ProductFavourite $previous = null;

    public function getCurrent(): ProductFavourite
    {
        return $this->current;
    }

    public function setCurrent(ProductFavourite $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?ProductFavourite
    {
        return $this->previous;
    }

    public function setPrevious(?ProductFavourite $previous): void
    {
        $this->previous = $previous;
    }
}
