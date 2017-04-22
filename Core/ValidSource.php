<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * Always valid (exists, writable, readable) file
 */
final class ValidSource implements Source {
	private $path;
	private $origin;

	public function __construct(string $path, Source $origin) {
		$this->path = $path;
		$this->origin = $origin;
	}

	public function read(): array {
		if (!is_readable($this->path) || !$this->isIni($this->path)) {
			throw new \InvalidArgumentException(
				sprintf(
					'File "%s" must be readable ini file',
					$this->path
				)
			);
		}
		return $this->origin->read();
	}

	public function write(array $values): void {
		if (!is_writable($this->path) || !$this->isIni($this->path)) {
			throw new \InvalidArgumentException(
				sprintf(
					'File "%s" must be writable ini file',
					$this->path
				)
			);
		}
		$this->origin->write($values);
	}

	public function remove($key, string $section = null): void {
		$this->origin->remove($key, $section);
	}

	/**
	 * Is the path valid ini file?
	 * @param string $path
	 * @return bool
	 */
	private function isIni(string $path): bool {
		return strcasecmp(pathinfo($path, PATHINFO_EXTENSION), 'ini') === 0;
	}
}