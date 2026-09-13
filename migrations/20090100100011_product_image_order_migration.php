<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;

final class ProductImageOrderMigration extends BasePantonoMigration
{
    public function change(): void
    {
        $this->tablePrefix('product_image')
            ->addColumn('display_order', 'integer', ['null' => true])
            ->update();
    }
}
