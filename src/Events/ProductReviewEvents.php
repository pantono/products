<?php

namespace Pantono\Products\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pantono\Products\Event\PostReviewSaveEvent;
use Pantono\Logger\AuditLogger;

class ProductReviewEvents implements EventSubscriberInterface
{
    private AuditLogger $log;

    public function __construct(AuditLogger $log)
    {
        $this->log = $log;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostReviewSaveEvent::class => [
                ['saveLog', 255]
            ]
        ];
    }

    public function saveLog(PostReviewSaveEvent $event): void
    {
        $current = $event->getCurrent();
        $previous = $event->getPrevious();
        if (!$previous) {
            $this->log->addLogForModel($current::class, (string)$current->getId(), 'Created new review');
            return;
        }

        if ($previous->isApproved() !== $current->isApproved()) {
            $this->log->addLogForModel($current::class, (string)$current->getId(), 'Changed approved from ' . ($previous->isApproved() ? 'Yes' : 'No') . ' to ' . ($current->isApproved() ? 'Yes' : 'No'), $previous->getAllData(), $current->getAllData());
        }
        if ($previous->getRating() !== $current->getRating()) {
            $this->log->addLogForModel($current::class, (string)$current->getId(), 'Changed rating from ' . $previous->getRating() . ' to ' . $current->getRating(), $previous->getAllData(), $current->getAllData());
        }
        if ($previous->getReviewContent() !== $current->getReviewContent()) {
            $this->log->addLogForModel($current::class, (string)$current->getId(), 'Changed review content from ' . $previous->getReviewContent() . ' to ' . $current->getReviewContent(), $previous->getAllData(), $current->getAllData());
        }
    }
}
