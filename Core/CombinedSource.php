<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * Multiple combined ini files behaving as a single one
 */
final class CombinedSource implements Source {
	private $origins;

	public function __construct(Source ...$origins) {
		$this->origins = $origins;
	}

	public function read(): array {
		return array_reduce(
			$this->origins,
			function(array $combined, Source $origin): array {
				return $this->merge($combined, $origin->read());
			},
			[]
		);
	}

	public function write(array $values): void {
		foreach ($this->origins as $origin)
			$origin->write($values);
	}

	public function remove($key, string $section = null): void {
		foreach ($this->origins as $origin)
			$origin->remove($key, $section);
	}

	private function merge(array $array1, array $array2): array {
		return array_reduce(
			array_keys($array2),
			function(array $array1, $key) use ($array2): array {
				if (is_array($array2[$key]) && isset($array1[$key]) && is_array($array1[$key]))
					$array1[$key] = $this->merge($array1[$key], $array2[$key]);
				else $array1[$key] = $array2[$key];
				return $array1;
			},
			$array1
		);
	}
}