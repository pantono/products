<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\ProductFieldType;

class AbstractProductFieldTypeSaveEvent extends Event
{
    private ProductFieldType $current;
    private ?ProductFieldType $previous = null;

    public function getCurrent(): ProductFieldType
    {
        return $this->current;
    }

    public function setCurrent(ProductFieldType $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?ProductFieldType
    {
        return $this->previous;
    }

    public function setPrevious(?ProductFieldType $previous): void
    {
        $this->previous = $previous;
    }
}
