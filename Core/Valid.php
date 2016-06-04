<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * This ini file is always valid (exists, writable, readable)
 * Class Valid
 * @package Klapuch\Ini
 */
final class Valid implements Ini {
    private $path;
    private $origin;

    /**
     * @param string $path
     * @param Ini $origin
     */
    public function __construct(string $path, Ini $origin) {
        $this->path = $path;
        $this->origin = $origin;
    }

    public function read(): array {
        if(!is_readable($this->path) || !$this->isIni()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'File "%s" must be readable ini file',
                    $this->path
                )
            );
        }
        return $this->origin->read();
    }

    public function write(array $values) {
        if(!is_writable($this->path) || !$this->isIni()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'File "%s" must be writable ini file',
                    $this->path
                )
            );
        }
        $this->origin->write($values);
    }

    public function remove(string $key, string $section = null) {
        $this->origin->remove($key, $section);
    }


    /**
     * Is the $this->path valid ini file?
     * @return bool
     */
    private function isIni(): bool {
        return strtolower(pathinfo($this->path, PATHINFO_EXTENSION)) === 'ini';
    }
}