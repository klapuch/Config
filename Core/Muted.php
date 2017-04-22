<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * Muted every error - suitable for files which may not exists
 */
final class Muted implements Ini {
	private $origin;

	public function __construct(Ini $origin) {
		$this->origin = $origin;
	}

	public function read(): array {
		return $this->asMuted(function() {
			return $this->origin->read();
		}, []);
	}

	public function write(array $values): void {
		$this->asMuted(function() use ($values) {
			$this->origin->write($values);
		});
	}

	public function remove($key, string $section = null): void {
		$this->asMuted(function() use ($key, $section) {
			$this->origin->remove($key, $section);
		});
	}

	/**
	 * Proceed mute action
	 * @param callable $action
	 * @param mixed $default
	 * @return mixed
	 */
	private function asMuted(callable $action, $default = []) {
		try {
			return $action();
		} catch (\Throwable $ex) {
			return $default;
		}
	}
}