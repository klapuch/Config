<?php
declare(strict_types = 1);
namespace Klapuch\Ini;

/**
 * This ini file can handle only with strings
 * Class Untyped
 * @package Klapuch\Ini
 */
final class Untyped implements Ini {
    private $origin;

    /**
     * @param Ini $origin
     */
    public function __construct(Ini $origin) {
        $this->origin = $origin;
    }

    public function read(): array {
        return $this->toUnitedType($this->origin->read());
    }

    public function write(array $values) {
        $this->origin->write($values);
    }

    public function remove(string $key, string $section = null) {
        $this->origin->remove($key, $section);
    }


    /**
     * The given array is transformed to united type
     * @param array $ini
     * @return array
     */
    private function toUnitedType(array $ini): array {
        return array_map(function($value) {
            return (string)$value;
        }, $ini);
    }
}