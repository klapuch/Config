<?php
declare(strict_types = 1);
namespace Klapuch\Configuration;

/**
 * Multiple combined sources behaving as a single one
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
}
