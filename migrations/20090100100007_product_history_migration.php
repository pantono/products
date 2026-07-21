<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;

final class ProductHistoryMigration extends BasePantonoMigration
{
    public function up(): void
    {
        $this->table($this->addTablePrefix('product_history'))
            ->addLinkedColumn('product_id', $this->addTablePrefix('product'), 'id')
            ->addColumn('date', 'datetime')
            ->addLinkedColumn('user_id', $this->addTablePrefix('user'), 'id', ['null' => false])
            ->addColumn('entry', 'text')
            ->create();
    }
}
