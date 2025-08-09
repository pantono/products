<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ProductsMigration extends AbstractMigration
{
    public function up(): void
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
            ->addColumn('in_review', 'boolean')
            ->addColumn('editable', 'boolean')
            ->addIndex('name', ['unique' => true])
            ->addIndex('visible')
            ->addIndex('archived')
            ->addIndex('purchasable')
            ->create();

        if ($this->isMigratingUp()) {
            $this->table('product_status')
                ->insert([
                    ['id' => 1, 'name' => 'Draft', 'visible' => false, 'purchasable' => false, 'archived' => false, 'in_review' => false, 'editable' => true],
                    ['id' => 2, 'name' => 'Awaiting Approval', 'visible' => false, 'purchasable' => false, 'archived' => false, 'in_review' => true, 'editable' => false],
                    ['id' => 3, 'name' => 'Approved', 'visible' => true, 'purchasable' => false, 'archived' => false, 'in_review' => true, 'editable' => false],
                    ['id' => 4, 'name' => 'Amendments Required', 'visible' => false, 'purchasable' => false, 'archived' => false, 'in_review' => true, 'editable' => true],
                    ['id' => 5, 'name' => 'Archived', 'visible' => false, 'purchasable' => false, 'archived' => true, 'in_review' => false, 'editable' => false],
                ])->saveData();;
        }

        $this->table('product_condition')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product_type')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product_brand')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product_version')
            ->addColumn('date_added', 'datetime')
            ->addColumn('date_updated', 'datetime')
            ->addColumn('product_id', 'integer')
            ->addColumn('type_id', 'integer', ['signed' => false])
            ->addColumn('title', 'string')
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
            ->addForeignKey('status_id', 'product_status', 'id')
            ->addForeignKey('vat_rate_id', 'product_vat_rate', 'id')
            ->addForeignKey('condition_id', 'product_condition', 'id')
            ->addForeignKey('brand_id', 'product_brand', 'id')
            ->addForeignKey('type_id', 'product_type', 'id')
            ->addForeignKey('company_id', 'company', 'id')
            ->create();

        $this->table('product_image', ['id' => false, 'primary_key' => ['product_id', 'image_id']])
            ->addColumn('version_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('image_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('main_image', 'boolean')
            ->addColumn('deleted', 'boolean')
            ->addForeignKey('version_id', 'product_version', 'id')
            ->addForeignKey('image_id', 'stored_file', 'id')
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
            ->addColumn('version_id', 'integer', ['signed' => false])
            ->addColumn('category_id', 'integer', ['signed' => false])
            ->addColumn('display_order', 'integer')
            ->addColumn('archived', 'boolean')
            ->addForeignKey('version_id', 'product_version', 'id')
            ->addForeignKey('category_id', 'category', 'id')
            ->create();

        $this->table('flag')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product_flag', ['id' => false, 'primary_key' => ['product_id', 'flag_id']])
            ->addColumn('version_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('flag_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('flag_id', 'flag', 'id')
            ->addForeignKey('version_id', 'product_version', 'id')
            ->create();

        $this->table('product')
            ->addColumn('date_created', 'datetime')
            ->addColumn('date_updated', 'datetime')
            ->addColumn('draft_id', 'integer', ['signed' => false])
            ->addColumn('code', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('published_draft_id', 'integer', ['signed' => false])
            ->addIndex('code', ['unique' => true])
            ->addIndex('slug', ['unique' => true])
            ->addForeignKey('draft_id', 'product_draft', 'id')
            ->addForeignKey('published_draft_id', 'product_draft', 'id')
            ->update();

        $this->table('product_version')
            ->addForeignKey('product_id', 'product', 'id')
            ->update();

        $this->table('product_related', ['id' => false, 'primary_key' => ['source_product', 'target_product']])
            ->addColumn('source_product', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('target_product', 'integer', ['null' => false, 'signed' => false])
            ->addForeignKey('source_product', 'product', 'id')
            ->addForeignKey('target_product', 'product', 'id')
            ->create();

        $this->table('product_version_history')
            ->addColumn('product_version_id', 'integer', ['signed' => false])
            ->addColumn('date', 'datetime')
            ->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('entry', 'text')
            ->create();
    }
}
