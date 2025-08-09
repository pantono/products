<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\ProductVersion;

abstract class AbstractProductVersionSaveEvent extends Event
{
    private ProductVersion $current;
    private ?ProductVersion $previous = null;

    public function getCurrent(): ProductVersion
    {
        return $this->current;
    }

    public function setCurrent(ProductVersion $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?ProductVersion
    {
        return $this->previous;
    }

    public function setPrevious(?ProductVersion $previous): void
    {
        $this->previous = $previous;
    }
}
