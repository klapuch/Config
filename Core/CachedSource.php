<?php
declare(strict_types = 1);
namespace Klapuch\Configuration;

/**
 * Cached source
 */
final class CachedSource implements Source {
	private $origin;
	private $hash;

	public function __construct(Source $origin) {
		$this->origin = $origin;
		$this->hash = spl_object_hash($origin);
	}

	public function read(): array {
		if (!apcu_exists($this->hash))
			apcu_store($this->hash, $this->origin->read());
		return apcu_fetch($this->hash);
	}
}