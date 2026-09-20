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
use Pantono\Products\Filter\DiscountFilter;
use Pantono\Products\Filter\DiscountCodeFilter;
use Pantono\Products\Model\Product;

class ProductDiscounts
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

    /**
     * @return SpecialOffer[]
     */
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

    /**
     * @return DiscountBase[]
     */
    public function getDiscountBaseList(): array
    {
        return $this->hydrator->hydrateSet(DiscountBase::class, $this->repository->getDiscountBaseList());
    }

    public function getDiscountById(int $id): ?Discount
    {
        return $this->hydrator->hydrate(Discount::class, $this->repository->getDiscountById($id));
    }

    /**
     * @return DiscountCode[]
     */
    public function getDiscountCodesByFilter(DiscountCodeFilter $filter): array
    {
        return $this->hydrator->hydrateSet(DiscountCode::class, $this->repository->getDiscountCodesByFilter($filter));
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

    /**
     * @return Discount[]
     */
    public function getDiscountsByFilter(DiscountFilter $filter): array
    {
        return $this->hydrator->hydrateSet(Discount::class, $this->repository->getDiscountsByFilter($filter));
    }

    public function updateAllOfferProducts(SpecialOffer $offer): int
    {
        $this->repository->clearProductsForOffer($offer);
        $total = 0;
        if ($offer->getDiscount()) {
            foreach ($this->getProductsForDiscount($offer->getDiscount()) as $product) {
                if ($product->getPublishedDraft()) {
                    $this->addProductToOffer($product->getPublishedDraft(), $offer);
                    $total++;
                }
            }
        }
        return $total;
    }

    /**
     * @param Discount $discount
     * @return Product[]
     */
    public function getProductsForDiscount(Discount $discount): array
    {
        $filter = $this->getProductFilterForDiscount($discount);
        $filter->setPerPage(99999);
        return $this->products->getProductsByFilter($filter);
    }

    public function getProductFilterForDiscount(Discount $discount): ProductFilter
    {
        $filter = new ProductFilter();
        $filter->setStatus($this->hydrator->lookupRecord(ProductStatus::class, ProductApproval::STATUS_APPROVED));
        $rules = $discount->getRules();
        foreach ($rules as $rule) {
            $value = $rule->getValue();
            if ($rule->getOperand() === 'in' || $rule->getReverseOperand() === 'in') {
                $value = explode(',', $value);
            }
            $filter->addColumn($rule->getField(), $value, $rule->isInclude() ? $rule->getOperand() : $rule->getReverseOperand());
        }
        return $filter;
    }
}
