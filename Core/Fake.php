<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

final class Fake implements Ini {
    private $ini;

    public function __construct(array $ini = []) {
        $this->ini = $ini;
    }

    public function read(): array {
        return $this->ini;
    }

    public function write(array $values) {
        $this->ini = array_merge($this->ini, $values);
    }
}