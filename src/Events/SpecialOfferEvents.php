<?php

namespace Pantono\Products\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pantono\Products\Event\PostProductVersionSaveEvent;
use Pantono\Products\ProductHistory;
use Pantono\Contracts\Security\SecurityContextInterface;
use Pantono\Products\Model\ProductVersion;
use Pantono\Utilities\StringUtilities;
use Pantono\Products\Discounts;
use Pantono\Products\Filter\SpecialOfferFilter;

class SpecialOfferEvents implements EventSubscriberInterface
{
    private Discounts $discounts;

    public function __construct(Discounts $discounts)
    {
        $this->discounts = $discounts;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostProductVersionSaveEvent::class => [
                ['checkSpecialOffers', -254]
            ]
        ];
    }

    public function checkSpecialOffers(PostProductVersionSaveEvent $event): void
    {
        $filter = new SpecialOfferFilter();
        $filter->setPerPage(9999);
        foreach ($this->discounts->getOffersByFilter($filter) as $offer) {
            if ($offer->isApplicable($event->getCurrent())) {
                $this->discounts->addProductToOffer($event->getCurrent(), $offer);
            }
        }
    }
}
