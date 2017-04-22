<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

interface Source {
	/**
	 * Read values from the ini file
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	public function read(): array;

	/**
	 * Write new values to the ini file
	 * @param array $values
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function write(array $values): void;

	/**
	 * Remove value and key by the given key and optionally by section
	 * @param mixed $key
	 * @param string|null $section
	 * @return void
	 */
	public function remove($key, string $section = null): void;
}