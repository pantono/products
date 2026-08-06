<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;

final class ProductFavouritesMigration extends BasePantonoMigration
{
    public function change(): void
    {
        $this->tablePrefix('product_favourite')
            ->addColumn('date_created', 'datetime', ['null' => false])
            ->addLinkedColumn('user_id', $this->addTablePrefix('user'), 'id')
            ->addLinkedColumn('product_id', $this->addTablePrefix('product'), 'id')
            ->addColumn('deleted', 'boolean')
            ->addIndex('product_id')
            ->addIndex('user_id')
            ->addIndex(['user_id', 'product_id'], ['unique' => true])
            ->create();
    }
}
