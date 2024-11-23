<?php

namespace rubencm\storage_db\adapter;

use phpbb\db\driver\driver_interface;
use phpbb\storage\adapter\adapter_interface;
use phpbb\storage\exception\storage_exception;


class db implements adapter_interface
{

	/**
	 * @var driver_interface db
	 */
	protected $db;

	/**
	 * @var string storage
	 */
	protected $storage;

	/**
	 * @var string table_prefix
	 */
	protected $table_prefix;

	/**
	 * Constructor
	 *
	 * @param driver_interface $db
	 * @param string $table_prefix
	 */
	public function __construct(driver_interface $db, string $table_prefix)
	{
		$this->db = $db;
		$this->table_prefix = $table_prefix;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configure(array $options): void
	{
		$this->storage = $options['storage'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function read(string $path)
	{
		$sql = "SELECT data
					FROM " . $this->table_prefix . "storage_files
					WHERE storage = '" . $this->db->sql_escape($this->storage) . "' AND path = '" . $this->db->sql_escape($path) . "'";

		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		// Simulate the stream
		$stream = fopen('php://temp', 'w+b');
		fwrite($stream, $row['data']);
		rewind($stream);

		return $stream;
	}

	/**
	 * {@inheritdoc}
	 */
	public function write(string $path, $resource): int
	{
		$this->delete($path);

		$data = stream_get_contents($resource);

		$sql = "INSERT INTO " . $this->table_prefix . "storage_files (storage, path, data)
					VALUES ('" . $this->db->sql_escape($this->storage) . "', '" . $this->db->sql_escape($path) . "', '" . $this->db->sql_escape($data) . "')";

		$this->db->sql_query($sql);

		return strlen($data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(string $path): void
	{
		$sql = "DELETE FROM " . $this->table_prefix . "storage_files
					WHERE storage = '" . $this->db->sql_escape($this->storage) . "' AND path = '" . $this->db->sql_escape($path) . "'";

		$this->db->sql_query($sql);
	}

	public function free_space(): float
	{
		throw new storage_exception('STORAGE_CANNOT_GET_FREE_SPACE');
	}
}
