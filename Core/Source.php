<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

interface Source {
	/**
	 * Read values from the ini file
	 * @throws \UnexpectedValueException
	 * @return array
	 */
	public function read(): array;
}