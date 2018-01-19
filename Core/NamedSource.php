<?php
declare(strict_types = 1);
namespace Klapuch\Configuration;

/**
 * Named generated source
 */
final class NamedSource implements Source {
	private $name;
	private $origin;


	public function __construct(string $name, Source $origin) {
		$this->name = $name;
		$this->origin = $origin;
	}

	public function read(): array {
		return [$this->name => $this->origin->read()];
	}
}