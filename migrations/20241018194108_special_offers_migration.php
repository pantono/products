<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SpecialOffersMigration extends AbstractMigration
{
    public function up(): void
    {
        $this->table('discount_base')
            ->addColumn('name', 'string')
            ->addColumn('percentage', 'boolean', ['default' => 0])
            ->addColumn('amount', 'boolean', ['default' => 0])
            ->addColumn('free_delivery', 'boolean', ['default' => 0])
            ->addColumn('buy_x_get_y', 'boolean', ['default' => 0])
            ->create();

        if ($this->isMigratingUp()) {
            $this->table('discount_base')
                ->insert([
                    ['id' => 1, 'name' => 'Percentage', 'percentage' => true],
                    ['id' => 2, 'name' => 'Fixed Amount', 'amount' => true],
                    ['id' => 3, 'name' => 'Free Delivery', 'free_delivery' => true],
                    ['id' => 4, 'name' => 'Buy X Get Y', 'buy_x_get_y' => true],
                ])->saveData();
        }

        $this->table('discount')
            ->addColumn('name', 'string')
            ->addColumn('base_id', 'integer', ['signed' => false])
            ->addColumn('amount', 'float', ['null' => true])
            ->addColumn('min_spend', 'float', ['null' => true])
            ->addColumn('max_spend', 'float', ['null' => true])
            ->addColumn('buy_x_min', 'integer', ['null' => true])
            ->addColumn('buy_x_free', 'integer', ['null' => true])
            ->addColumn('priority', 'integer', ['default' => 1])
            ->addColumn('stack', 'boolean')
            ->addColumn('live', 'boolean')
            ->addColumn('deleted', 'boolean')
            ->addForeignKey('base_id', 'discount_base', 'id')
            ->create();

        $this->table('discount_rule')
            ->addColumn('discount_id', 'integer', ['signed' => false])
            ->addColumn('field', 'string')
            ->addColumn('value', 'text')
            ->addColumn('operand', 'string')
            ->addColumn('include', 'boolean')
            ->addForeignKey('discount_id', 'discount', 'id')
            ->create();

        $this->table('discount_code')
            ->addColumn('code', 'string')
            ->addColumn('discount_id', 'integer', ['signed' => false])
            ->addColumn('start_date', 'datetime')
            ->addColumn('end_date', 'datetime')
            ->addColumn('max_uses', 'integer')
            ->addIndex(['code'], ['unique' => true])
            ->addForeignKey('discount_id', 'discount', 'id')
            ->create();

        $this->table('discount_code_usage')
            ->addColumn('discount_id', 'integer', ['signed' => false])
            ->addColumn('order_id', 'integer', ['signed' => false])
            ->addColumn('date_used', 'datetime')
            ->addForeignKey('discount_id', 'discount', 'id')
            ->create();

        $this->table('special_offer')
            ->addColumn('name', 'string')
            ->addColumn('discount_id', 'integer', ['signed' => false])
            ->addColumn('start_date', 'datetime')
            ->addColumn('end_date', 'datetime')
            ->addColumn('active', 'boolean')
            ->addForeignKey('discount_id', 'discount', 'id')
            ->create();

        $this->table('special_offer_product')
            ->addColumn('special_offer_id', 'integer', ['signed' => false])
            ->addColumn('product_version_id', 'integer', ['signed' => false])
            ->addForeignKey('special_offer_id', 'special_offer', 'id')
            ->addForeignKey('product_version_id', 'product_version', 'id')
            ->create();
    }
}
