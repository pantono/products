<?php

namespace Pantono\Products\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pantono\Products\Event\PostProductVersionSaveEvent;
use Pantono\Products\ProductHistory;
use Pantono\Contracts\Security\SecurityContextInterface;
use Pantono\Products\Model\ProductVersion;
use Pantono\Utilities\StringUtilities;

class ProductVersionEvents implements EventSubscriberInterface
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
            PostProductVersionSaveEvent::class => [
                ['saveProductVersionHistory', -255]
            ]
        ];
    }

    public function saveProductVersionHistory(PostProductVersionSaveEvent $event): void
    {
        if (!$event->getPrevious()) {
            $this->logHistory($event->getCurrent(), 'Created new version');
            return;
        }
        $previous = $event->getPrevious();
        $current = $event->getCurrent();
        if ($current->getTitle() !== $previous->getTitle()) {
            $this->logHistory($current, 'Changed title from ' . $previous->getTitle() . ' to ' . $current->getTitle());;
        }
        foreach ($current->diff($previous) as $field => $info) {
            $name = StringUtilities::camelCaseToWords($field);
            $old = $info['old'] ?: 'N/A';
            $new = $info['new'] ?: 'N/A';
            $this->logHistory($current, 'Changed ' . $name . ' from ' . $old . ' to ' . $new);;
        }
    }

    private function logHistory(ProductVersion $version, string $entry): void
    {
        $this->productHistory->addHistoryToProductVersion($version, $this->securityContext->get('user'), $entry);
    }
}
