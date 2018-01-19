<?php
declare(strict_types = 1);
namespace Klapuch\Configuration;

/**
 * Always valid (exists, readable) json file
 */
final class ValidJson implements Source {
	private $file;

	public function __construct(\SplFileInfo $file) {
		$this->file = $file;
	}

	public function read(): array {
		if (!$this->file->isFile()) {
			throw new \UnexpectedValueException(
				sprintf(
					'File "%s" does not exist',
					$this->file
				)
			);
		}
		$json = json_decode(file_get_contents($this->file->getPathname()), true);
		if ($json === null) {
			throw new \UnexpectedValueException(
				sprintf(
					'File "%s" is not in json format',
					$this->file
				)
			);
		}
		return $json;
	}
}