<?php
declare(strict_types = 1);
namespace Klapuch;

interface Ini {
    /**
     * Read values from the ini file
     * @throws \InvalidArgumentException
     * @return array
     */
    public function read(): array;

    /**
     * Write new values to the ini file
     * @param array $values
     * @return void
     */
    public function write(array $values);
}