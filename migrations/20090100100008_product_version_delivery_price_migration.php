<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;

final class ProductVersionDeliveryPriceMigration extends BasePantonoMigration
{
    public function change(): void
    {
        $this->table($this->addTablePrefix('product_version'))
            ->addColumn('delivery_price', 'float', ['null' => true])
            ->update();
    }
}
