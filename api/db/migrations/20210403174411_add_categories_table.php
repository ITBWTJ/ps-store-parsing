<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddCategoriesTable extends AbstractMigration
{
    public function up()
    {
        $exists = $this->hasTable('categories');

        if (!$exists) {
            $table = $this->table('categories', ['id' => true]);
            $table
                ->addColumn('name', MysqlAdapter::PHINX_TYPE_STRING, ['null' => false])
                ->addColumn('store_id', MysqlAdapter::PHINX_TYPE_STRING, ['null' => false])
                ->addColumn('image_url', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
                ->addColumn('link_target', MysqlAdapter::PHINX_TYPE_STRING, ['null' => false])
                ->addColumn('deleted_at', MysqlAdapter::PHINX_TYPE_TIMESTAMP, ['null' => true])
                ->addColumn('created_at', MysqlAdapter::PHINX_TYPE_TIMESTAMP, ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('updated_at', MysqlAdapter::PHINX_TYPE_TIMESTAMP, ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
                ->save();
        }
    }

    public function down() {
        $exists = $this->hasTable('categories');
        if ($exists) {
            $this->table('categories')->drop()->save();
        }
    }
}
