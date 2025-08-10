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

        $this->table('product_status')
            ->insert([
                ['id' => 1, 'name' => 'Draft', 'visible' => false, 'purchasable' => false, 'archived' => false, 'in_review' => false, 'editable' => true],
                ['id' => 2, 'name' => 'Awaiting Approval', 'visible' => false, 'purchasable' => false, 'archived' => false, 'in_review' => true, 'editable' => false],
                ['id' => 3, 'name' => 'Approved', 'visible' => true, 'purchasable' => false, 'archived' => false, 'in_review' => true, 'editable' => false],
                ['id' => 4, 'name' => 'Amendments Required', 'visible' => false, 'purchasable' => false, 'archived' => false, 'in_review' => true, 'editable' => true],
                ['id' => 5, 'name' => 'Archived', 'visible' => false, 'purchasable' => false, 'archived' => true, 'in_review' => false, 'editable' => false],
            ])->saveData();

        // Create all other tables without the circular foreign keys first
        $this->createBaseTables();

        $this->table('category_status')
            ->insert([
                ['id' => 1, 'name' => 'Live', 'visible' => 1],
                ['id' => 2, 'name' => 'Draft', 'visible' => 0],
                ['id' => 3, 'name' => 'Archived', 'visible' => 0],
            ])->saveData();

        // Create the product and product_version tables without foreign keys first
        $this->table('product_version')
            ->addColumn('date_added', 'datetime')
            ->addColumn('date_updated', 'datetime')
            ->addColumn('product_id', 'integer', ['signed' => false])
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

        $this->table('product')
            ->addColumn('date_created', 'datetime')
            ->addColumn('date_updated', 'datetime')
            ->addColumn('draft_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('published_draft_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('code', 'string')
            ->addColumn('slug', 'string')
            ->addIndex('code', ['unique' => true])
            ->addIndex('slug', ['unique' => true])
            ->create();

        // Now add the circular foreign keys
        $this->table('product')
            ->addForeignKey('draft_id', 'product_version', 'id')
            ->addForeignKey('published_draft_id', 'product_version', 'id')
            ->update();

        $this->table('product_version')
            ->addForeignKey('product_id', 'product', 'id')
            ->update();

        $this->createDependentTables();
    }

    public function down(): void
    {
        // Drop dependent tables first
        $this->dropDependentTables();

        // Remove circular foreign keys in correct order
        $this->table('product_version')
            ->dropForeignKey('product_id')
            ->dropForeignKey('brand_id')
            ->dropForeignKey('status_id')
            ->dropForeignKey('condition_id')
            ->dropForeignKey('type_id')
            ->dropForeignKey('company_id')
            ->dropForeignKey('vat_rate_id')
            ->update();

        $this->table('product')
            ->dropForeignKey('draft_id')
            ->dropForeignKey('published_draft_id')
            ->update();

        // Now we can drop the main tables
        $this->table('product')->drop()->update();
        $this->table('product_version')->drop()->update();

        // Drop all other tables
        $this->dropBaseTables();
    }

    private function createBaseTables(): void
    {
        $this->table('product_condition')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product_type')
            ->addColumn('name', 'string')
            ->create();

        $this->table('product_brand')
            ->addColumn('name', 'string')
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

        $this->table('flag')
            ->addColumn('name', 'string')
            ->create();
    }

    private function createDependentTables(): void
    {
        $this->table('product_image', ['id' => false, 'primary_key' => ['version_id', 'image_id']])
            ->addColumn('version_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('image_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('main_image', 'boolean')
            ->addColumn('deleted', 'boolean')
            ->addForeignKey('version_id', 'product_version', 'id')
            ->addForeignKey('image_id', 'stored_file', 'id')
            ->create();

        $this->table('product_category')
            ->addColumn('version_id', 'integer', ['signed' => false])
            ->addColumn('category_id', 'integer', ['signed' => false])
            ->addColumn('display_order', 'integer')
            ->addColumn('archived', 'boolean')
            ->addForeignKey('version_id', 'product_version', 'id')
            ->addForeignKey('category_id', 'category', 'id')
            ->create();

        $this->table('product_flag', ['id' => false, 'primary_key' => ['version_id', 'flag_id']])
            ->addColumn('version_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('flag_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('flag_id', 'flag', 'id')
            ->addForeignKey('version_id', 'product_version', 'id')
            ->create();

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

    private function dropDependentTables(): void
    {
        $this->table('product_version_history')->drop()->update();
        $this->table('product_related')->drop()->update();
        $this->table('product_flag')->drop()->update();
        $this->table('product_category')->drop()->update();
        $this->table('product_image')->drop()->update();
    }

    private function dropBaseTables(): void
    {
        $this->table('flag')->drop()->update();
        $this->table('category')->drop()->update();
        $this->table('category_status')->drop()->update();
        $this->table('product_brand')->drop()->update();
        $this->table('product_type')->drop()->update();
        $this->table('product_condition')->drop()->update();
        $this->table('product_status')->drop()->update();
        $this->table('product_vat_rate')->drop()->update();
    }
}
