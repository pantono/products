<?php

declare(strict_types=1);

use Pantono\Database\Migration\Base\BasePantonoMigration;

final class VatRateDefaultMigration extends BasePantonoMigration
{
    public function change(): void
    {
        $this->tablePrefix('product_vat_rate')
            ->addColumn('default', 'boolean', ['default' => false])
            ->update();
        if ($this->isMigratingUp()) {
            $this->query('UPDATE product_vat_rate SET default = TRUE WHERE id = 1');
        }
    }
}
