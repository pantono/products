<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ProductStatsMigration extends AbstractMigration
{
    public function change(): void
    {
        $this->table('product_stat_type')
            ->addColumn('name', 'string')
            ->create();

        if ($this->isMigratingUp()) {
            $this->table('product_stat_type')
                ->insert([
                    ['id' => 1, 'name' => 'Viewed'],
                    ['id' => 2, 'name' => 'Added to Cart'],
                    ['id' => 3, 'name' => 'Purchased'],
                    ['id' => 4, 'name' => 'Added to Wishlist'],
                    ['id' => 5, 'name' => 'Listed on homepage'],
                    ['id' => 6, 'name' => 'Listed category page'],
                ])->saveData();
        }

        $this->table('product_stat')
            ->addColumn('date', 'datetime')
            ->addColumn('type_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('product_version_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('product_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('user_id', 'integer', ['signed' => false, 'null' => true])
            ->addForeignKey('product_version_id', 'product_version', 'id')
            ->addForeignKey('product_id', 'product', 'id')
            ->addForeignKey('type_id', 'product_stat_type', 'id')
            ->addIndex('date')
            ->addIndex('user_id')
            ->addIndex('product_version_id')
            ->create();


        $this->table('product_stat_grouped', ['id' => false, 'primary_key' => ['date', 'type_id', 'product_version_id']])
            ->addColumn('date', 'date', ['null' => false])
            ->addColumn('type_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('product_version_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('product_id', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('count', 'integer')
            ->addForeignKey('type_id', 'product_stat_type', 'id')
            ->addForeignKey('product_version_id', 'product_version', 'id')
            ->addForeignKey('product_id', 'product', 'id')
            ->create();
    }
}
