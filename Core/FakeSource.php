<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * Fake
 */
final class FakeSource implements Source {
	private $ini;

	public function __construct(array $ini = null) {
		$this->ini = $ini;
	}

	public function read(): array {
		return $this->ini;
	}
}