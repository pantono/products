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

        $productTable = $this->addTablePrefix('product');
        $versionTable = $this->addTablePrefix('product_version');
        if ($this->getAdapter()->getAdapterType() === 'mysql') {
            $this->query('UPDATE ' . $productTable . ' p INNER JOIN ' . $versionTable . ' v on (p.published_draft_id=v.id OR (p.published_draft_id IS NULL AND p.draft_id=v.id)) SET p.stock_holding=v.stock_holding');
        } else {
            $this->query('UPDATE ' . $productTable . ' SET stock_holding = v.stock_holding FROM ' . $versionTable . ' v WHERE (' . $productTable . '.published_draft_id=v.id OR (' . $productTable . '.published_draft_id IS NULL AND ' . $productTable . '.draft_id=v.id))');
        }

        $this->table($this->addTablePrefix('product_version'))
            ->removeColumn('stock_holding')
            ->update();

        $this->table($this->addTablePrefix('product_stock_movement'))
            ->addLinkedColumn('product_id', $this->addTablePrefix('product'), 'id')
            ->addLinkedColumn('user_id', $this->addTablePrefix('user'), 'id', ['null' => true])
            ->addColumn('order_id', 'integer')
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

        $productTable = $this->addTablePrefix('product');
        $versionTable = $this->addTablePrefix('product_version');
        if ($this->getAdapter()->getAdapterType() === 'mysql') {
            $this->query('UPDATE ' . $productTable . ' p INNER JOIN ' . $versionTable . ' v on (p.published_draft_id=v.id OR (p.published_draft_id IS NULL AND p.draft_id=v.id)) SET v.stock_holding=p.stock_holding');
        } else {
            $this->query('UPDATE ' . $versionTable . ' SET stock_holding = p.stock_holding FROM ' . $productTable . ' p WHERE (p.published_draft_id=' . $versionTable . '.id OR (p.published_draft_id IS NULL AND p.draft_id=' . $versionTable . '.id))');
        }

        $this->table($this->addTablePrefix('product'))
            ->removeColumn('stock_holding')
            ->update();

        $this->table($this->addTablePrefix('product_stock_movement'))
            ->drop()->update();
    }
}
