<?php

namespace Pantono\Products;

use Pantono\Hydrator\Hydrator;
use Pantono\Products\Model\ProductReview;
use Pantono\Products\Event\PreReviewSaveEvent;
use Pantono\Products\Repository\ReviewsRepository;
use Pantono\Products\Event\PostReviewSaveEvent;
use Pantono\Products\Filter\ReviewFilter;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Reviews
{
    private Hydrator $hydrator;
    private EventDispatcher $dispatcher;
    private ReviewsRepository $repository;

    public function __construct(Hydrator $hydrator, EventDispatcher $dispatcher, ReviewsRepository $repository)
    {
        $this->hydrator = $hydrator;
        $this->dispatcher = $dispatcher;
        $this->repository = $repository;
    }


    /**
     * @return ProductReview[]
     */
    public function getReviewsByFilter(ReviewFilter $filter): array
    {
        return $this->hydrator->hydrateSet(ProductReview::class, $this->repository->getReviewsByFilter($filter));
    }

    public function getReviewById(int $id): ?ProductReview
    {
        return $this->hydrator->lookupRecord(ProductReview::class, $id);
    }

    public function saveReview(ProductReview $review): void
    {
        $previous = $review->getId() ? $this->hydrator->lookupRecord(ProductReview::class, $review->getId()) : null;
        $event = new PreReviewSaveEvent();
        $event->setCurrent($review);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);

        $this->repository->saveModel($review);

        $event = new PostReviewSaveEvent();
        $event->setCurrent($review);
        $event->setPrevious($previous);
        $this->dispatcher->dispatch($event);
    }
}
