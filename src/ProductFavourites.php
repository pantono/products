<?php

namespace Pantono\Products;

use Pantono\Products\Repository\ProductFavouritesRepository;
use Pantono\Hydrator\Hydrator;
use Pantono\Products\Model\Product;
use Pantono\Authentication\Model\User;
use Pantono\Products\Model\ProductFavourite;
use Pantono\Products\Event\PreProductFavouriteSaveEvent;
use Pantono\Products\Event\PostProductFavouriteSaveEvent;
use Pantono\Products\Filter\ProductFavouriteFilter;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ProductFavourites
{
    private ProductFavouritesRepository $repository;
    private Hydrator $hydrator;
    private EventDispatcher $dispatcher;

    public function __construct(ProductFavouritesRepository $repository, Hydrator $hydrator, EventDispatcher $dispatcher)
    {
        $this->repository = $repository;
        $this->hydrator = $hydrator;
        $this->dispatcher = $dispatcher;
    }

    public function addUserFavourite(Product $product, User $user): ProductFavourite
    {
        $favourite = new ProductFavourite();
        $favourite->setProduct($product);
        $favourite->setUser($user);
        $this->saveFavourite($favourite);
        return $favourite;
    }

    /**
     * @return ProductFavourite[]
     */
    public function getFavouritesByFilter(ProductFavouriteFilter $filter): array
    {
        return $this->hydrator->hydrateSet(ProductFavourite::class, $this->repository->getFavouritesByFilter($filter));
    }

    public function getFavouriteCountByFilter(ProductFavouriteFilter $filter): int
    {
        return $this->repository->getFavouriteCountByFilter($filter);
    }

    public function getFavouriteForUserAndProduct(User $user, Product $product): ?ProductFavourite
    {
        return $this->hydrator->lookupRecord(ProductFavourite::class, $this->repository->getFavouriteForUserAndProduct($user, $product));
    }

    public function saveFavourite(ProductFavourite $favourite): void
    {
        $previous = $favourite->getId() ? $this->hydrator->lookupRecord(ProductFavourite::class, $favourite->getId()) : null;
        $event = new PreProductFavouriteSaveEvent();
        $event->setPrevious($previous);
        $event->setCurrent($favourite);
        $this->dispatcher->dispatch($event);

        $this->repository->saveModel($favourite);

        $event = new PostProductFavouriteSaveEvent();
        $event->setPrevious($previous);
        $event->setCurrent($favourite);
        $this->dispatcher->dispatch($event);
    }
}
