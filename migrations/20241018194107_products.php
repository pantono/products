<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Products extends AbstractMigration
{
    public function change(): void
    {
        $this->table('product_vat_rate')
            ->addColumn('name', 'string')
            ->addColumn('rate', 'float')
            ->create();

        $this->table('product_status')
            ->addColumn('name', 'string')
            ->addColumn('visible', 'boolean')
            ->addColumn('purchasable', 'boolean')
            ->addColumn('archived', 'boolean')
            ->addIndex('name', ['unique' => true])
            ->addIndex('visible')
            ->addIndex('archived')
            ->addIndex('purchasable')
            ->create();

        $this->table('product_condition')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product_type')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product_brand')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product')
            ->addColumn('date_added', 'datetime')
            ->addColumn('date_updated', 'datetime')
            ->addColumn('type_id', 'integer', ['signed' => false])
            ->addColumn('title', 'string')
            ->addColumn('code', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('description', 'string')
            ->addColumn('status_id', 'integer', ['signed' => false])
            ->addColumn('vat_rate_id', 'integer', ['signed' => false])
            ->addColumn('weight', 'float')
            ->addColumn('stock_holding', 'integer')
            ->addColumn('meta_description', 'text', ['null' => true])
            ->addColumn('meta_title', 'text', ['null' => true])
            ->addColumn('meta_keywords', 'text', ['null' => true])
            ->addColumn('meta_robots', 'text', ['null' => true])
            ->addColumn('brand_id', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('condition_id', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('price', 'float', ['null' => true])
            ->addColumn('rrp', 'float', ['null' => true])
            ->addColumn('company_id', 'integer', ['null' => true, 'signed' => false])
            ->addIndex('code', ['unique' => true])
            ->addIndex('slug', ['unique' => true])
            ->addForeignKey('status_id', 'product_status', 'id')
            ->addForeignKey('vat_rate_id', 'product_vat_rate', 'id')
            ->addForeignKey('condition_id', 'product_condition', 'id')
            ->addForeignKey('brand_id', 'product_brand', 'id')
            ->addForeignKey('type_id', 'product_type', 'id')
            ->addForeignKey('company_id', 'company', 'id')
            ->create();

        $this->table('product_image', ['id' => false, 'primary_key' => ['product_id', 'image_id']])
            ->addColumn('product_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('image_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('main_image', 'boolean')
            ->addColumn('deleted', 'boolean')
            ->addForeignKey('product_id', 'product', 'id')
            ->addForeignKey('image_id', 'stored_file', 'id')
            ->create();

        $this->table('product_auction')
            ->addColumn('product_id', 'integer')
            ->addColumn('auction_start_date', 'datetime')
            ->addColumn('auction_end_date', 'datetime')
            ->addColumn('auction_start_bid', 'float')
            ->addColumn('auction_processed', 'boolean')
            ->addColumn('auction_estimate_start', 'float')
            ->addColumn('auction_estimate_end', 'float')
            ->create();

        $this->table('product_related', ['id' => false, 'primary_key' => ['source_product', 'target_product']])
            ->addColumn('source_product', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('target_product', 'integer', ['null' => false, 'signed' => false])
            ->addForeignKey('source_product', 'product', 'id')
            ->addForeignKey('target_product', 'product', 'id')
            ->create();

        $this->table('category_status')
            ->addColumn('name', 'string')
            ->addColumn('visible', 'boolean')
            ->create();

        $this->table('category')
            ->addColumn('title', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('description', 'text')
            ->addColumn('meta_description', 'text', ['null' => true])
            ->addColumn('meta_title', 'text', ['null' => true])
            ->addColumn('meta_keywords', 'text', ['null' => true])
            ->addColumn('meta_robots', 'text', ['null' => true])
            ->addColumn('image_id', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('status_id', 'integer', ['signed' => false, 'null' => false])
            ->addIndex('slug', ['unique' => true])
            ->addForeignKey('image_id', 'stored_file', 'id')
            ->addForeignKey('status_id', 'category_status', 'id')
            ->create();

        $this->table('product_category')
            ->addColumn('product_id', 'integer', ['signed' => false])
            ->addColumn('category_id', 'integer', ['signed' => false])
            ->addColumn('display_order', 'integer')
            ->addColumn('archived', 'boolean')
            ->addForeignKey('product_id', 'product', 'id')
            ->addForeignKey('category_id', 'category', 'id')
            ->create();

        $this->table('flag')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product_flag', ['id' => false, 'primary_key' => ['product_id', 'flag_id']])
            ->addColumn('product_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('flag_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('flag_id', 'flag', 'id')
            ->addForeignKey('product_id', 'product', 'id')
            ->create();
    }
}
