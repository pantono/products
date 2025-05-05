<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\Category;

class AbstractCategorySaveEvent extends Event
{
    private Category $current;
    private ?Category $previous;

    public function getCurrent(): Category
    {
        return $this->current;
    }

    public function setCurrent(Category $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?Category
    {
        return $this->previous;
    }

    public function setPrevious(?Category $previous): void
    {
        $this->previous = $previous;
    }
}
