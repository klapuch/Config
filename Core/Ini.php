<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

interface Ini {
	/**
	 * Reads values from the ini file
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	public function read(): array;

	/**
	 * Writes new values to the ini file
	 * @param array $values
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function write(array $values);

	/**
	 * Removes value and key by the given key and optionally by section
	 * @param mixed $key
	 * @param string|null $section
	 * @return void
	 */
	public function remove($key, string $section = null);
}