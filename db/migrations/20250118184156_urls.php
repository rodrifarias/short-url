<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Urls extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('urls', ['id' => true, 'primary_key' => 'id']);
        $table->addColumn('origin', 'string', ['limit' => 1024, 'null' => false]);
        $table->addColumn('redirect_url', 'string', ['limit' => 64, 'null' => false]);
        $table->addColumn('created_at', 'datetime', ['null' => false]);
        $table->addIndex('origin', ['unique' => true]);
        $table->addIndex('redirect_url', ['unique' => true]);
        $table->create();
    }
}
