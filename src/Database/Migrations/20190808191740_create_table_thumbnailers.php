<?php namespace Tatter\Thumbnails\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_thumbnailers extends Migration
{
	public function up()
	{
		$fields = [
			'name'           => ['type' => 'varchar', 'constraint' => 31],
			'uid'            => ['type' => 'varchar', 'constraint' => 31],
			'class'          => ['type' => 'varchar', 'constraint' => 63],
			'summary'        => ['type' => 'varchar', 'constraint' => 255],
			'extensions'     => ['type' => 'varchar', 'constraint' => 255],
			'created_at'     => ['type' => 'datetime', 'null' => true],
			'updated_at'     => ['type' => 'datetime', 'null' => true],
			'deleted_at'     => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('name');
		$this->forge->addKey('uid');
		$this->forge->addKey(['deleted_at', 'id']);
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('thumbnailers');
	}

	public function down()
	{
		$this->forge->dropTable('thumbnailers');
	}
}
