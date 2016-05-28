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

final class TypedIni extends Tester\TestCase {
    public function testReading() {
        $ini = $this->preparedIni();
        Assert::same(
            [],
            (new Klapuch\TypedIni($ini))
            ->read()
        );
        file_put_contents($ini, 'foo = bar');
        Assert::same(
            ['foo' => 'bar'],
            (new Klapuch\TypedIni($ini))
                ->read()
        );
    }

    public function testReadingWithSections() {
        $ini = $this->preparedIni();
        file_put_contents(
            $ini,
            '[SECTION]foo = bar'
        );
        Assert::same(
            ['SECTION' => ['foo' => 'bar']],
            (new Klapuch\TypedIni($ini))
                ->read()
        );
    }

    public function testReadingWithProperType() {
        $ini = $this->preparedIni();
        file_put_contents(
            $ini,
            "number = 666 \r\n text = some string \r\n boolean = true"
        );
        Assert::same(
            ['number' => 666, 'text' => 'some string', 'boolean' => true],
            (new Klapuch\TypedIni($ini))
                ->read()
        );
    }

    public function testWriting() {
        $ini = $this->preparedIni();
        (new Klapuch\TypedIni($ini))->write(['foo' => 'bar', 'bar' => 'foo']);
        Assert::same("foo=bar\r\nbar=foo\r\n", file_get_contents($ini));
    }

    public function testWritingWithSection() {
        $ini = $this->preparedIni();
        (new Klapuch\TypedIni($ini))->write(
            ['SECTION' => ['foo' => 'bar', 'bar' => 'foo']]
        );
        Assert::same(
            "[SECTION]\r\nfoo=bar\r\nbar=foo\r\n",
            file_get_contents($ini)
        );
    }

    public function testWritingMultipleSections() {
        $ini = $this->preparedIni();
        (new Klapuch\TypedIni($ini))->write(
            ['SECTION' => ['foo' => 'bar'], 'SECTION2' => ['bar' => 'foo']]
        );
        Assert::same(
            "[SECTION]\r\nfoo=bar\r\n[SECTION2]\r\nbar=foo\r\n",
            file_get_contents($ini)
        );
    }

    public function testWritingToExistingValues() {
        $ini = $this->preparedIni();
        file_put_contents($ini, "klapuch=666\r\nyou=1");
        (new Klapuch\TypedIni($ini))->write(['foo' => 'bar']);
        Assert::same(
            "klapuch=666\r\nyou=1\r\nfoo=bar\r\n",
            file_get_contents($ini)
        );
    }

    private function preparedIni() {
        return Tester\FileMock::create('', 'ini');
    }
}


(new TypedIni())->run();
