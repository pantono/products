<?php

namespace Pantono\Products;

use Pantono\Products\Model\Product;
use Pantono\Products\Model\ProductVersion;

class ProductApproval
{
    private Products $products;

    public const int STATUS_DRAFT = 1;
    public const int STATUS_AWAITING_APPROVAL = 2;
    public const int STATUS_APPROVED = 3;
    public const int STATUS_DECLINED = 4;
    public const int STATUS_ARCHIVED = 4;

    public function __construct(Products $products)
    {
        $this->products = $products;
    }

    public function approveProductVersion(ProductVersion $version): void
    {
        $status = $this->products->getStatusById(self::STATUS_APPROVED);
        if (!$status) {
            throw new \RuntimeException('Approved status does not exist');
        }
        $product = $this->products->getProductById($version->getProductId());
        if (!$product) {
            throw new \RuntimeException('Product does not exist');
        }
        $version->setStatus($status);
        $this->products->saveProductVersion($version);
        $product->setPublishedDraft($version);
        $this->products->saveProduct($product);
    }

    public function declineProductVersion(ProductVersion $version): void
    {
        $status = $this->products->getStatusById(self::STATUS_DECLINED);
        if (!$status) {
            throw new \RuntimeException('Declined status does not exist');
        }
        $product = $this->products->getProductById($version->getProductId());
        if (!$product) {
            throw new \RuntimeException('Product does not exist');
        }
        $version->setStatus($status);
    }
}
