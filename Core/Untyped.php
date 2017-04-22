<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * All types are considered as a string
 */
final class Untyped implements Ini {
	private $origin;

	public function __construct(Ini $origin) {
		$this->origin = $origin;
	}

	public function read(): array {
		return $this->toUnitedType($this->origin->read());
	}

	public function write(array $values): void {
		$this->origin->write($values);
	}

	public function remove($key, string $section = null): void {
		$this->origin->remove($key, $section);
	}

	/**
	 * The given array is transformed to united type
	 * @param array $ini
	 * @return array
	 */
	private function toUnitedType(array $ini): array {
		return array_map('strval', $ini);
	}
}