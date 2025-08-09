<?php

namespace Pantono\Products;

use Pantono\Products\Repository\ProductHistoryRepository;
use Pantono\Products\Model\ProductVersion;
use Pantono\Authentication\Model\User;
use Pantono\Products\Filter\ProductHistoryFilter;
use Pantono\Hydrator\Hydrator;
use Pantono\Products\Model\ProductVersionHistory;

class ProductHistory
{
    private ProductHistoryRepository $repository;
    private Hydrator $hydrator;

    public function __construct(ProductHistoryRepository $repository, Hydrator $hydrator)
    {
        $this->repository = $repository;
        $this->hydrator = $hydrator;
    }

    public function addHistoryToProductVersion(ProductVersion $version, User $user, string $entry): void
    {
        $this->repository->saveHistoryForVersion($version, $user, $entry);
    }

    /**
     * @param ProductHistoryFilter $filter
     * @return ProductVersionHistory[]
     */
    public function getHistoryByFilter(ProductHistoryFilter $filter): array
    {
        return $this->hydrator->hydrateSet(ProductVersionHistory::class, $this->repository->getHistoryByFilter($filter));
    }

    public function getEntryById(int $id): ?ProductVersionHistory
    {
        return $this->hydrator->hydrate(ProductVersionHistory::class, $this->repository->getEntryById($id));
    }
}
