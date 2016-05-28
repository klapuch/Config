<?php
declare(strict_types = 1);
namespace Klapuch;

interface Ini {
    /**
     * Reads values from the ini file
     * @throws \InvalidArgumentException
     * @return array
     */
    public function read(): array;

    /**
     * Writes new values to the ini file
     * @param array $values
     * @throws \InvalidArgumentException
     * @return void
     */
    public function write(array $values);
}