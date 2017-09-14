<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * Can handle with types
 */
final class TypedSource implements Source {
	private const PARSE_SECTIONS = true;
	private const CRLF = "\r\n";
	private $ini;

	public function __construct(\SplFileInfo $ini) {
		$this->ini = $ini;
	}

	public function read(): array {
		return parse_ini_file(
			$this->ini->getPathname(),
			self::PARSE_SECTIONS,
			INI_SCANNER_TYPED
		);
	}

	public function write(array $values): void {
		file_put_contents($this->ini->getPathname(), $this->toIni($this->read() + $values));
	}

	public function remove($key, string $section = null): void {
		$ini = $this->read();
		if ($section === null)
			unset($ini[$key]);
		unset($ini[$section][$key]);
		file_put_contents($this->ini->getPathname(), $this->toIni($ini));
	}

	/**
	 * Convert the array to ini format
	 * @param array $values
	 * @return string
	 */
	private function toIni(array $values): string {
		return implode(
			array_map(
				function(string $key, $value): string {
					return $this->isArray($value)
						? sprintf('[%s]', $key) . self::CRLF . $this->toIni($value)
						: sprintf('%s=%s', $key, $value) . self::CRLF;
				},
				array_keys($values),
				$values
			)
		);
	}

	/**
	 * Is the given value an array?
	 * @param mixed $value
	 * @return bool
	 */
	private function isArray($value): bool {
		return (array) $value === $value;
	}
}