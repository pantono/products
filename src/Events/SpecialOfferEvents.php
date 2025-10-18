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
use Pantono\Products\Event\PostDiscountSaveEvent;
use Pantono\Products\Event\PostSpecialOfferSaveEvent;

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
                ['checkSpecialOffers', -254],
            ],
            PostDiscountSaveEvent::class => [
                ['updateProducts', -250]
            ],
            PostSpecialOfferSaveEvent::class => [
                ['updateProductsForOffer', -200]
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

    public function updateProducts(PostDiscountSaveEvent $event): void
    {
        $filter = new SpecialOfferFilter();
        $filter->setDiscount($event->getCurrent());
        foreach ($this->discounts->getOffersByFilter($filter) as $offer) {
            $this->discounts->updateAllOfferProducts($offer);
        }
    }

    public function updateProductsForOffer(PostSpecialOfferSaveEvent $event): void
    {
        $this->discounts->updateAllOfferProducts($event->getCurrent());
    }
}
