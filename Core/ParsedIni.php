<?php
declare(strict_types = 1);
namespace Klapuch;

final class ParsedIni implements Ini {
    const PARSE_SECTIONS = true;
    const CRLF = "\r\n";
    private $path;

    /**
     * @param string $path
     */
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
        file_put_contents(
            $this->path,
            $this->toIni(array_merge($this->read(), $values))
        );
    }

    /**
     * Converts array to ini string
     * @param array $values
     * @return string
     */
    private function toIni(array $values): string {
        $ini = '';
        foreach($values as $key => $value) {
            if($this->isArray($value)) {
                $ini .= sprintf('[%s]', $key) . self::CRLF;
                $ini .= $this->toIni($value);
            }
            else
                $ini .= sprintf('%s=%s', $key, $value) . self::CRLF;
        }
        return $ini;
    }

    /**
     * Is the given value array?
     * @param $value
     * @return bool
     */
    private function isArray($value): bool {
        return (array)$value === $value;
    }
}