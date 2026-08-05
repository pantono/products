<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\ProductReview;

abstract class AbstractReviewSaveEvent extends Event
{
    private ProductReview $current;
    private ?ProductReview $previous = null;

    public function getCurrent(): ProductReview
    {
        return $this->current;
    }

    public function setCurrent(ProductReview $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?ProductReview
    {
        return $this->previous;
    }

    public function setPrevious(?ProductReview $previous): void
    {
        $this->previous = $previous;
    }
}
