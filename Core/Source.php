<?php
declare(strict_types = 1);
namespace Klapuch\Configuration;

interface Source {
	/**
	 * Read values from the source
	 * @throws \UnexpectedValueException
	 * @return array
	 */
	public function read(): array;
}