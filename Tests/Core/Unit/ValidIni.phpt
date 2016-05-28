<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Klapuch\Unit;

use Tester,
    Tester\Assert;
use Klapuch;

require __DIR__ . '/../../bootstrap.php';

final class ValidIni extends Tester\TestCase {

    /**
     * @throws \InvalidArgumentException File "unknownFile.ini" must be readable ini file
     */
    public function testReadingFromUnknownFile() {
        (new Klapuch\ValidIni('unknownFile.ini', new Klapuch\FakeIni))->read();
    }

    /**
     * @throws \InvalidArgumentException File "mock://1.txt" must be readable ini file
     */
    public function testReadingFromNonIniFile() {
        (new Klapuch\ValidIni($this->preparedTxt(), new Klapuch\FakeIni))->read();
    }


    /**
     * @throws \InvalidArgumentException File "unknownFile.ini" must be writable ini file
     */
    public function testWritingToUnknownFile() {
        (new Klapuch\ValidIni('unknownFile.ini', new Klapuch\FakeIni))->write(['foo' => 'bar']);
    }

    /**
     * @throws \InvalidArgumentException File "mock://1.txt" must be writable ini file
     */
    public function testWritingToNonIniFile() {
        (new Klapuch\ValidIni($this->preparedTxt(), new Klapuch\FakeIni))->write(['foo' => 'bar']);
    }

    private function preparedTxt() {
        return Tester\FileMock::create('', 'txt');
    }
}


(new ValidIni)->run();
