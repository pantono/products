<?php

namespace Pantono\Products\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Products\Model\SpecialOffer;

abstract class AbstractSpecialOfferSaveEvent extends Event
{
    private SpecialOffer $current;
    private ?SpecialOffer $previous = null;

    public function getCurrent(): SpecialOffer
    {
        return $this->current;
    }

    public function setCurrent(SpecialOffer $current): void
    {
        $this->current = $current;
    }

    public function getPrevious(): ?SpecialOffer
    {
        return $this->previous;
    }

    public function setPrevious(?SpecialOffer $previous): void
    {
        $this->previous = $previous;
    }
}
