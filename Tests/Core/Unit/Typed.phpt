<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Klapuch\Unit;

use Klapuch\Ini;
use Tester;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

final class Typed extends Tester\TestCase {
	public function testSuccessfulReading() {
		$ini = $this->preparedIni();
		Assert::same([], (new Ini\Typed($ini))->read());
		file_put_contents($ini, 'foo = bar');
		Assert::same(['foo' => 'bar'], (new Ini\Typed($ini))->read());
	}

	public function testReadingWithSections() {
		$ini = $this->preparedIni();
		file_put_contents($ini, '[SECTION]foo = bar');
		Assert::same(
			['SECTION' => ['foo' => 'bar']],
			(new Ini\Typed($ini))->read()
		);
	}

	public function testCorrectTypes() {
		$ini = $this->preparedIni();
		file_put_contents(
			$ini,
			"number=666\r\ntext=some string\r\nbool=true\r\n10=2"
		);
		Assert::same(
			['number' => 666, 'text' => 'some string', 'bool' => true, 10 => 2],
			(new Ini\Typed($ini))->read()
		);
	}

	public function testSuccessfulWriting() {
		$ini = $this->preparedIni();
		(new Ini\Typed($ini))->write(['foo' => 'bar', 'bar' => 'foo']);
		Assert::same("foo=bar\r\nbar=foo\r\n", file_get_contents($ini));
	}

	public function testWritingWithSection() {
		$ini = $this->preparedIni();
		(new Ini\Typed($ini))->write(
			['SECTION' => ['foo' => 'bar', 'bar' => 'foo']]
		);
		Assert::same(
			"[SECTION]\r\nfoo=bar\r\nbar=foo\r\n",
			file_get_contents($ini)
		);
	}

	public function testWritingWithMultipleSections() {
		$ini = $this->preparedIni();
		(new Ini\Typed($ini))->write(
			['SECTION' => ['foo' => 'bar'], 'SECTION2' => ['bar' => 'foo']]
		);
		Assert::same(
			"[SECTION]\r\nfoo=bar\r\n[SECTION2]\r\nbar=foo\r\n",
			file_get_contents($ini)
		);
	}

	public function testWritingToExistingValues() {
		$ini = $this->preparedIni();
		file_put_contents($ini, "me=666\r\nyou=1");
		(new Ini\Typed($ini))->write(['foo' => 'bar']);
		Assert::same("me=666\r\nyou=1\r\nfoo=bar\r\n", file_get_contents($ini));
	}

	public function testWritingWithoutDuplication() {
		$ini = $this->preparedIni();
		file_put_contents($ini, "me=666\r\nyou=1");
		(new Ini\Typed($ini))->write(['me' => 666]);
		(new Ini\Typed($ini))->write(['me' => 666]);
		Assert::same("me=666\r\nyou=1\r\n", file_get_contents($ini));
	}

	public function testRemovingByExistingKey() {
		$ini = $this->preparedIni();
		file_put_contents($ini, "me=666\r\nyou=1\r\nwe=2");
		(new Ini\Typed($ini))->remove('you');
		Assert::same("me=666\r\nwe=2\r\n", file_get_contents($ini));
	}

	public function testRemovingByUnknownKey() {
		$ini = $this->preparedIni();
		file_put_contents($ini, "me=666\r\nyou=1\r\nwe=2");
		(new Ini\Typed($ini))->remove('xxxxxxxxxxxxxxxxxxxxxxxxx');
		Assert::same("me=666\r\nyou=1\r\nwe=2\r\n", file_get_contents($ini));
	}

	public function testRemovingByExistingKeyInSection() {
		$ini = $this->preparedIni();
		file_put_contents($ini, "foo=666\r\n[SECTION]\r\nfoo=123\r\n");
		(new Ini\Typed($ini))->remove('foo', 'SECTION');
		Assert::same("foo=666\r\n[SECTION]\r\n", file_get_contents($ini));
	}

	private function preparedIni() {
		return Tester\FileMock::create('', 'ini');
	}
}

(new Typed())->run();
