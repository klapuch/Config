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
		return array_replace_recursive(
			...array_map(
				function(Source $source): array {
					return $source->read();
				},
				$this->origins
			)
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

}
