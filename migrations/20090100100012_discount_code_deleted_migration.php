<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;

final class DiscountCodeDeletedMigration extends BasePantonoMigration
{
    public function change(): void
    {
        $this->tablePrefix('discount_code')
            ->addColumn('deleted', 'boolean', ['default' => false])
            ->update();
    }
}
