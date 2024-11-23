<?php


namespace rubencm\storage_db\migrations;

class m1_initial_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'storage_files');
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v400\dev'];
	}

	public function update_data()
	{
		return [['custom', [[$this, 'add_storage_files_table']]]];
	}

	public function revert_schema()
	{
		return [
			'drop_tables'	=> [
				$this->table_prefix . 'storage_files'
			],
		];
	}

	public function add_storage_files_table()
	{
		$sql = 'CREATE TABLE ' . $this->table_prefix . 'storage_files (
			file_id		INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			storage		VARCHAR(255) NOT NULL,
			path		VARCHAR(255) NOT NULL,
			data		BLOB NOT NULL,
			UNIQUE (storage, path)
		);';

		$this->db->sql_query($sql);
	}
}
