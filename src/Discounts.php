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
use Pantono\Products\Filter\ProductFilter;
use Pantono\Products\Model\ProductStatus;
use Pantono\Products\Event\PreSpecialOfferSaveEvent;
use Pantono\Products\Event\PostSpecialOfferSaveEvent;

class Discounts
{
    private DiscountsRepository $repository;
    private Hydrator $hydrator;
    private EventDispatcher $dispatcher;
    private Products $products;

    public function __construct(DiscountsRepository $repository, Hydrator $hydrator, EventDispatcher $dispatcher, Products $products)
    {
        $this->repository = $repository;
        $this->hydrator = $hydrator;
        $this->dispatcher = $dispatcher;
        $this->products = $products;
    }

    public function getOffersForProductVersion(ProductVersion $version): array
    {
        return $this->hydrator->hydrateSet(SpecialOffer::class, $this->repository->getOffersForProductVersion($version));
    }

    public function getOffersByFilter(SpecialOfferFilter $filter): array
    {
        return $this->hydrator->hydrateSet(SpecialOffer::class, $this->repository->getOffersByFilter($filter));
    }

    public function getSpecialOfferById(int $id): ?SpecialOffer
    {
        return $this->hydrator->hydrate(SpecialOffer::class, $this->repository->getSpecialOfferById($id));
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

    public function saveSpecialOffer(SpecialOffer $offer): void
    {
        $previous = $offer->getId() ? $this->getSpecialOfferById($offer->getId()) : null;
        $event = new PreSpecialOfferSaveEvent();
        $event->setPrevious($previous);
        $event->setCurrent($offer);
        $this->dispatcher->dispatch($event);

        $this->repository->saveSpecialOffer($offer);

        $event = new PostSpecialOfferSaveEvent();
        $event->setPrevious($previous);
        $event->setCurrent($offer);
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

    public function updateAllOfferProducts(SpecialOffer $offer): int
    {
        $this->repository->clearProductsForOffer($offer);
        $filter = new ProductFilter();
        $filter->setStatus($this->hydrator->lookupRecord(ProductStatus::class, ProductApproval::STATUS_APPROVED));
        $rules = $offer->getDiscount() ? $offer->getDiscount()->getRules() : [];
        foreach ($rules as $rule) {
            $filter->addColumn($rule->getField(), $rule->getValue(), $rule->isInclude() ? $rule->getOperand() : $rule->getReverseOperand());
        }
        $total = 0;
        foreach ($this->products->getProductsByFilter($filter) as $product) {
            $this->addProductToOffer($product->getPublishedDraft(), $offer);
            $total++;
        }
        return $total;
    }
}
