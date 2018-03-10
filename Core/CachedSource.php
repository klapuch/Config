<?php
declare(strict_types = 1);
namespace Klapuch\Configuration;

/**
 * Cached source
 */
final class CachedSource implements Source {
	private $origin;
	private $name;

	public function __construct(Source $origin, string $name) {
		$this->origin = $origin;
		$this->name = sprintf('klapuch:configuration:%s', $name);
	}

	public function read(): array {
		if (!apcu_exists($this->name))
			apcu_store($this->name, $this->origin->read());
		return apcu_fetch($this->name);
	}
}