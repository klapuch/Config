<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * Always valid (exists, writable, readable) file
 */
final class ValidSource implements Source {
	private $file;
	private $origin;

	public function __construct(\SplFileInfo $file, Source $origin) {
		$this->file = $file;
		$this->origin = $origin;
	}

	public function read(): array {
		if (!$this->file->isReadable() || !$this->isIni($this->file)) {
			throw new \InvalidArgumentException(
				sprintf(
					'File "%s" must be readable ini file',
					$this->file
				)
			);
		}
		return $this->origin->read();
	}

	public function write(array $values): void {
		if (!$this->file->isWritable() || !$this->isIni($this->file)) {
			throw new \InvalidArgumentException(
				sprintf(
					'File "%s" must be writable ini file',
					$this->file
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
	 * @param \SplFileInfo $file
	 * @return bool
	 */
	private function isIni(\SplFileInfo $file): bool {
		return strcasecmp($file->getExtension(), 'ini') === 0;
	}
}