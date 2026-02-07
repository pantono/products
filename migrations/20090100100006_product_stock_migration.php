<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;

final class ProductStockMigration extends BasePantonoMigration
{
    public function up(): void
    {
        $this->table($this->addTablePrefix('product'))
            ->addColumn('stock_holding', 'integer')
            ->update();

        $this->query('UPDATE ' . $this->addTablePrefix('product') . ' p INNER JOIN ' . $this->addTablePrefix('product_version') . ' v on p.published_draft_id=v.id SET p.stock_holding=v.stock_holding');

        $this->table($this->addTablePrefix('product_version'))
            ->removeColumn('stock_holding')
            ->update();

        $this->table($this->addTablePrefix('product_stock_movement'))
            ->addLinkedColumn('product_id', $this->addTablePrefix('product'), 'id')
            ->addLinkedColumn('user_id', $this->addTablePrefix('user'), 'id', ['null' => true])
            ->addLinkedColumn('order_id', $this->addTablePrefix('order'), 'id', ['null' => true])
            ->addColumn('date', 'datetime')
            ->addColumn('value', 'integer')
            ->addColumn('comments', 'text', ['null' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table($this->addTablePrefix('product_version'))
            ->addColumn('stock_holding', 'integer')
            ->update();

        $this->query('UPDATE ' . $this->addTablePrefix('product') . ' p INNER JOIN ' . $this->addTablePrefix('product_version') . ' v on p.published_draft_id=v.id SET v.stock_holding=p.stock_holding');

        $this->table($this->addTablePrefix('product'))
            ->removeColumn('stock_holding')
            ->update();

        $this->table($this->addTablePrefix('product_stock_movement'))
            ->drop()->update();
    }
}
