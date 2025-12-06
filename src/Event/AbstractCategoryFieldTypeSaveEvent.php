<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\CategoryFieldType;

abstract class AbstractCategoryFieldTypeSaveEvent extends Event
{
    private CategoryFieldType $current;
    private ?CategoryFieldType $previous = null;

    public function getCurrent(): CategoryFieldType
    {
        return $this->current;
    }

    public function setCurrent(CategoryFieldType $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?CategoryFieldType
    {
        return $this->previous;
    }

    public function setPrevious(?CategoryFieldType $previous): void
    {
        $this->previous = $previous;
    }
}
