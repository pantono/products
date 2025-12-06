<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CategoryFields extends AbstractMigration
{
    public function change(): void
    {
        $this->table('category_field_type')
            ->addColumn('name', 'string')
            ->addColumn('label', 'string')
            ->addColumn('type', 'string')
            ->addColumn('regex', 'string', ['null' => true])
            ->addColumn('allowed_values', 'json')
            ->addColumn('allowed_values_query', 'string', ['null' => true])
            ->addColumn('ui_visible', 'boolean', ['default' => 1])
            ->create();

        $this->table('category_field', ['id' => false, 'primary_key' => ['category_id', 'type_id']])
            ->addColumn('category_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('type_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('value', 'text')
            ->addForeignKey('category_id', 'category', 'id')
            ->addForeignKey('type_id', 'category_field_type', 'id')
            ->create();
    }
}
