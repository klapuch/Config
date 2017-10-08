<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * Cached source
 */
final class CachedSource implements Source {
	private $origin;
	private $read;

	public function __construct(Source $origin) {
		$this->origin = $origin;
	}

	public function read(): array {
		if ($this->read === null)
			$this->read = $this->origin->read();
		return $this->read;
	}

	public function write(array $values): void {
		$this->origin->write($values);
	}

	public function remove($key, string $section = null): void {
		$this->origin->remove($key, $section);
	}
}