<?php
declare(strict_types = 1);
namespace Klapuch;

final class ValidIni implements Ini {
    private $path;
    private $origin;

    public function __construct(string $path, Ini $origin) {
        $this->path = $path;
        $this->origin = $origin;
    }

    public function read(): array {
        if(is_readable($this->path) && $this->isIni())
            return $this->origin->read();
        throw new \InvalidArgumentException(
            sprintf(
                'File "%s" must be readable ini file',
                $this->path
            )
        );
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

    private function isIni(): bool {
        return strtolower(pathinfo($this->path, PATHINFO_EXTENSION)) === 'ini';
    }
}