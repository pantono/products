<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ProductFieldsMigration extends AbstractMigration
{
    public function change(): void
    {
        $this->table('product_field_type')
            ->addColumn('name', 'string')
            ->addColumn('label', 'string')
            ->addColumn('type', 'string')
            ->addColumn('regex', 'string', ['null' => true])
            ->addColumn('allowed_values', 'json')
            ->addColumn('allowed_values_query', 'string', ['null' => true])
            ->create();

        $this->table('product_field', ['id' => false, 'primary_key' => ['product_version_id', 'type_id']])
            ->addColumn('product_version_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('type_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('value', 'text')
            ->addForeignKey('type_id', 'product_field_type', 'id')
            ->addForeignKey('product_version_id', 'product_version', 'id')
            ->create();
    }
}
