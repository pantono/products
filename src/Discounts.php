<?php

namespace Pantono\Products;

use Pantono\Products\Repository\DiscountsRepository;
use Pantono\Hydrator\Hydrator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pantono\Products\Model\DiscountBase;
use Pantono\Products\Model\Discount;
use Pantono\Products\Model\DiscountCode;
use Pantono\Products\Model\DiscountRule;
use Pantono\Products\Event\PreDiscountSaveEvent;
use Pantono\Products\Event\PostDiscountSaveEvent;
use Pantono\Products\Event\PreDiscountCodeSaveEvent;
use Pantono\Products\Event\PostDiscountCodeSaveEvent;
use Pantono\Products\Model\ProductVersion;
use Pantono\Products\Filter\SpecialOfferFilter;
use Pantono\Products\Model\SpecialOffer;

class Discounts
{
    private DiscountsRepository $repository;
    private Hydrator $hydrator;
    private EventDispatcher $dispatcher;

    public function __construct(DiscountsRepository $repository, Hydrator $hydrator, EventDispatcher $dispatcher)
    {
        $this->repository = $repository;
        $this->hydrator = $hydrator;
        $this->dispatcher = $dispatcher;
    }

    public function getOffersForProductVersion(ProductVersion $version): array
    {
        return $this->hydrator->hydrateSet(SpecialOffer::class, $this->repository->getOffersForProductVersion($version));
    }

    public function getOffersByFilter(SpecialOfferFilter $filter): array
    {
        return $this->hydrator->hydrate(SpecialOffer::class, $this->repository->getOffersByFilter($filter));
    }

    public function getDiscountBaseById(int $id): ?DiscountBase
    {
        return $this->hydrator->hydrate(DiscountBase::class, $this->repository->getDiscountBaseById($id));
    }

    public function getDiscountById(int $id): ?Discount
    {
        return $this->hydrator->hydrate(Discount::class, $this->repository->getDiscountById($id));
    }

    public function getDiscountCodeById(int $id): ?DiscountCode
    {
        return $this->hydrator->hydrate(DiscountCode::class, $this->repository->getDiscountCodeById($id));
    }

    public function getDiscountCodeByCode(string $code): ?DiscountCode
    {
        return $this->hydrator->hydrate(DiscountCode::class, $this->repository->getDiscountCodeByCode($code));
    }

    public function getRulesForDiscount(Discount $discount): array
    {
        return $this->hydrator->hydrateSet(DiscountRule::class, $this->repository->getRulesForDiscount($discount));
    }

    public function saveDiscount(Discount $discount): void
    {
        $previous = $discount->getId() ? $this->getDiscountById($discount->getId()) : null;
        $event = new PreDiscountSaveEvent();
        $event->setPrevious($previous);
        $event->setCurrent($discount);
        $this->dispatcher->dispatch($event);

        $this->repository->saveDiscount($discount);

        $event = new PostDiscountSaveEvent();
        $event->setPrevious($previous);
        $event->setCurrent($discount);
        $this->dispatcher->dispatch($event);
    }

    public function saveDiscountCode(DiscountCode $code): void
    {
        $previous = $code->getId() ? $this->getDiscountCodeById($code->getId()) : null;
        $event = new PreDiscountCodeSaveEvent();
        $event->setPrevious($previous);
        $event->setCurrent($code);
        $this->dispatcher->dispatch($event);

        $this->repository->saveDiscountCode($code);

        $event = new PostDiscountCodeSaveEvent();
        $event->setPrevious($previous);
        $event->setCurrent($code);
        $this->dispatcher->dispatch($event);
    }

    public function logDiscountCodeUsage(Discount $discount, int $orderId): void
    {
        $this->repository->logDiscountCodeUsed($discount, $orderId);
    }

    public function addProductToOffer(ProductVersion $version, SpecialOffer $offer): void
    {
        $this->repository->addProductToOffer($version, $offer);
    }
}
