<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddGamesTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function change()
    {

        $table = $this->table('games', array('id' => true));
        $table
            ->addColumn('name', MysqlAdapter::PHINX_TYPE_STRING, ['null' => false])
            ->addColumn('store_id', MysqlAdapter::PHINX_TYPE_STRING, ['null' => false])
            ->addColumn('type', MysqlAdapter::PHINX_TYPE_STRING, ['null' => false])
            ->addColumn('base_price', MysqlAdapter::PHINX_TYPE_INTEGER, ['null' => false, 'signed' => false])
            ->addColumn('discounted_price', MysqlAdapter::PHINX_TYPE_INTEGER,
                ['null' => true, 'default' => 0, 'signed' => false])
            ->addColumn('end_time', MysqlAdapter::PHINX_TYPE_INTEGER, ['null' => true, 'signed' => false])
            ->addColumn('is_exclusive', MysqlAdapter::PHINX_TYPE_BOOLEAN, ['null' => false, 'default' => false])
            ->addColumn('platforms', MysqlAdapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('image_url', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->addColumn('concept', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->addColumn('deleted_at', MysqlAdapter::PHINX_TYPE_TIMESTAMP, ['null' => true])
            ->addColumn('created_at', MysqlAdapter::PHINX_TYPE_TIMESTAMP, ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', MysqlAdapter::PHINX_TYPE_TIMESTAMP,
                ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['store_id'], ['unique' => true])
            ->save();

    }
}
