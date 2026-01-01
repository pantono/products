<?php

namespace Pantono\Products\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pantono\Products\Event\PostCategorySaveEvent;
use Pantono\Contracts\Application\Cache\ApplicationCacheInterface;

class CategoryEvents implements EventSubscriberInterface
{
    private ApplicationCacheInterface $cache;

    public function __construct(ApplicationCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostCategorySaveEvent::class => [['clearCache', -255]],
        ];
    }

    public function clearCache(PostCategorySaveEvent $event): void
    {
        try {
            $this->cache->delete('category_' . $event->getCurrent()->getId());
            $this->cache->delete('category_children_' . $event->getCurrent()->getId());
            $this->cache->delete('category_slug_' . $event->getCurrent()->getSlug());
            if ($event->getPrevious() && $event->getPrevious()->getSlug() !== $event->getCurrent()->getSlug()) {
                $this->cache->delete('category_slug_' . $event->getPrevious()->getSlug());
            }
        } catch (\Exception $e) {

        }
    }
}
