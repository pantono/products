<?php

namespace Pantono\Products\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pantono\Products\ProductHistory;
use Pantono\Contracts\Security\SecurityContextInterface;
use Pantono\Products\Model\Product;
use Pantono\Products\Event\PostProductSaveEvent;

class ProductEvents implements EventSubscriberInterface
{
    private ProductHistory $productHistory;
    private SecurityContextInterface $securityContext;

    public function __construct(ProductHistory $productHistory, SecurityContextInterface $securityContext)
    {
        $this->productHistory = $productHistory;
        $this->securityContext = $securityContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostProductSaveEvent::class => [
                ['saveProductHistory', -255]
            ]
        ];
    }

    public function saveProductVersionHistory(PostProductSaveEvent $event): void
    {
        if (!$event->getPrevious()) {
            $this->logHistory($event->getCurrent(), 'Created new product');
            return;
        }
        $previous = $event->getPrevious();
        $current = $event->getCurrent();
        if ($current->getStockHolding() !== $previous->getStockHolding()) {
            $this->logHistory($current, 'Changed stock holding from ' . $previous->getStockHolding() . ' to ' . $current->getStockHolding());
        }
        if ($current->getCode() !== $previous->getCode()) {
            $this->logHistory($current, 'Changed code from ' . $previous->getCode() . ' to ' . $current->getCode());
        }
        if ($current->getSlug() !== $previous->getSlug()) {
            $this->logHistory($current, 'Changed slug from ' . $previous->getSlug() . ' to ' . $current->getSlug());
        }
        if ($current->getPublishedDraft() && $previous->getPublishedDraft() && $previous->getPublishedDraft()->getId() === $current->getPublishedDraft()->getId()) {
            $this->logHistory($current, 'Changed published draft from ' . $previous->getPublishedDraft()->getId() . ' to ' . $current->getPublishedDraft()->getId());
        }

        if ($current->getDraft() && $previous->getDraft() && $current->getDraft()->getId() !== $previous->getDraft()->getId()) {
            $this->logHistory($current, 'Created new draft version: ' . $current->getDraft()->getId());
        }
    }

    private function logHistory(Product $product, string $entry): void
    {
        $this->productHistory->addHistoryToProduct($product, $this->securityContext->get('user'), $entry);
    }
}
