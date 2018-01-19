<?php
declare(strict_types = 1);
namespace Klapuch\Configuration;

/**
 * Always valid (exists, readable) ini file
 */
final class ValidIni implements Source {
	private const PARSE_SECTIONS = true;
	private $file;

	public function __construct(\SplFileInfo $file) {
		$this->file = $file;
	}

	public function read(): array {
		$ini = @parse_ini_file(
			$this->file->getPathname(),
			self::PARSE_SECTIONS,
			INI_SCANNER_TYPED
		);
		if ($ini === false) {
			throw new \UnexpectedValueException(
				sprintf(
					'File "%s" is not in ini format or does not exist',
					$this->file
				)
			);
		}
		return $ini;
	}
}