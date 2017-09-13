<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * Fake
 */
final class FakeSource implements Source {
	private $ini;

	public function __construct(array $ini = []) {
		$this->ini = $ini;
	}

	public function read(): array {
		return $this->ini;
	}

	public function write(array $values): void {
		$this->ini = $this->ini + $values;
	}

	public function remove($key, string $section = null): void {
		if ($section === null)
			unset($this->ini[$key]);
		unset($this->ini[$section][$key]);
	}
}