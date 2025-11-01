<?php

namespace Pantono\Products;

use Pantono\Products\Repository\ProductStatsRepository;
use Pantono\Hydrator\Hydrator;
use Pantono\Products\Model\ProductStatType;
use Pantono\Products\Model\ProductVersion;
use Pantono\Authentication\Model\User;

class ProductStats
{
    private ProductStatsRepository $repository;
    private Hydrator $hydrator;

    public const int STAT_TYPE_VIEWED = 1;
    public const int STAT_TYPE_ADDED_TO_CART = 2;
    public const int STAT_TYPE_PURCHASED = 3;
    public const int STAT_TYPE_ADDED_TO_WISHLIST = 4;
    public const int STAT_TYPE_LISTED_HOMEPAGE = 5;
    public const int STAT_TYPE_LISTED = 6;

    public function __construct(ProductStatsRepository $repository, Hydrator $hydrator)
    {
        $this->repository = $repository;
        $this->hydrator = $hydrator;
    }

    public function getStatTypeById(int $id): ?ProductStatType
    {
        return $this->hydrator->hydrate(ProductStatType::class, $this->repository->getStatTypById($id));
    }

    public function logStatTypeById(int $statTypeId, ProductVersion $version, ?User $user = null, \DateTimeInterface $date = new \DateTimeImmutable()): void
    {
        $type = $this->getStatTypeById($statTypeId);
        if (!$type) {
            throw new \RuntimeException('Product stat type does not exist');
        }
        $this->logStatType($type, $version, $user, $date);
    }

    public function logStatType(ProductStatType $statType, ProductVersion $version, ?User $user = null, \DateTimeInterface $date = new \DateTimeImmutable()): void
    {
        $this->repository->logStatForProduct($version, $statType, $user?->getId(), $date);
    }

    public function groupStats(ProductStatType $type, \DateTimeInterface $date): void
    {
        $this->repository->groupStats($type, $date);
    }
}
