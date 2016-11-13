<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * Can handle with types
 */
final class Typed implements Ini {
	const PARSE_SECTIONS = true;
	const CRLF = "\r\n";
	private $path;

	public function __construct(string $path) {
		$this->path = $path;
	}

	public function read(): array {
		return parse_ini_file(
			$this->path,
			self::PARSE_SECTIONS,
			INI_SCANNER_TYPED
		);
	}

	public function write(array $values) {
		file_put_contents($this->path, $this->toIni($this->read() + $values));
	}

	public function remove($key, string $section = null) {
		$ini = $this->read();
		if($section === null)
			unset($ini[$key]);
		else
			unset($ini[$section][$key]);
		file_put_contents($this->path, $this->toIni($ini));
	}

	/**
	 * Converts the array to ini format
	 * @param array $values
	 * @return string
	 */
	private function toIni(array $values): string {
		$ini = '';
		foreach($values as $key => $value) {
			if($this->isArray($value)) {
				$ini .= sprintf('[%s]', $key) . self::CRLF;
				$ini .= $this->toIni($value);
			} else
				$ini .= sprintf('%s=%s', $key, $value) . self::CRLF;
		}
		return $ini;
	}

	/**
	 * Is the given value an array?
	 * @param $value
	 * @return bool
	 */
	private function isArray($value): bool {
		return (array)$value === $value;
	}
}