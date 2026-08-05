<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;

final class ProductReviewMigration extends BasePantonoMigration
{
    public function change(): void
    {
        $this->tablePrefix('product_review')
            ->addColumn('date_created', 'datetime', ['null' => false])
            ->addLinkedColumn('user_id', $this->addTablePrefix('user'), 'id')
            ->addLinkedColumn('product_id', $this->addTablePrefix('product'), 'id')
            ->addColumn('title', 'string', ['null' => true])
            ->addColumn('rating', 'integer', ['null' => true])
            ->addColumn('review_content', 'text')
            ->addColumn('approved', 'boolean')
            ->addIndex('product_id')
            ->addIndex('user_id')
            ->create();
    }
}
