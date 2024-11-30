<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace rubencm\storage_db\provider;

use phpbb\language\language;
use phpbb\storage\provider\provider_interface;

class db implements provider_interface
{
	/**
	 * @var language
	 */
	protected $language;

	/**
	 * Constructor
	 *
	 * @param language $language
	 */
	public function __construct(language $language)
	{
		$this->language = $language;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_name(): string
	{
		return 'db';
	}

	public function get_title(): string
	{
		return $this->language->lang('STORAGE_ADAPTER_DB_NAME');
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_adapter_class(): string
	{
		return \rubencm\storage_db\adapter\db::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_options(): array
	{
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_available(): bool
	{
		return true;
	}

}
