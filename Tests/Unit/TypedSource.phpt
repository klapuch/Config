<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.1.0
 */
namespace Klapuch\Ini\Unit;

use Klapuch\Ini;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class TypedSource extends Tester\TestCase {
	public function testSuccessfulReading(): void {
		$ini = $this->preparedIni();
		Assert::same([], (new Ini\TypedSource(new \SplFileInfo($ini)))->read());
		file_put_contents($ini, 'foo = bar');
		Assert::same(['foo' => 'bar'], (new Ini\TypedSource(new \SplFileInfo($ini)))->read());
	}

	public function testReadingWithSections(): void {
		$ini = $this->preparedIni();
		file_put_contents($ini, '[SECTION]foo = bar');
		Assert::same(
			['SECTION' => ['foo' => 'bar']],
			(new Ini\TypedSource(new \SplFileInfo($ini)))->read()
		);
	}

	public function testCorrectTypes(): void {
		$ini = $this->preparedIni();
		file_put_contents(
			$ini,
			"number=666\r\ntext=some string\r\nbool=true\r\n10=2"
		);
		Assert::same(
			['number' => 666, 'text' => 'some string', 'bool' => true, 10 => 2],
			(new Ini\TypedSource(new \SplFileInfo($ini)))->read()
		);
	}

	public function testSuccessfulWriting(): void {
		$ini = $this->preparedIni();
		(new Ini\TypedSource(new \SplFileInfo($ini)))->write(['foo' => 'bar', 'bar' => 'foo']);
		Assert::same("foo=bar\r\nbar=foo\r\n", file_get_contents($ini));
	}

	public function testWritingWithSection(): void {
		$ini = $this->preparedIni();
		(new Ini\TypedSource(new \SplFileInfo($ini)))->write(
			['SECTION' => ['foo' => 'bar', 'bar' => 'foo']]
		);
		Assert::same(
			"[SECTION]\r\nfoo=bar\r\nbar=foo\r\n",
			file_get_contents($ini)
		);
	}

	public function testWritingWithMultipleSections(): void {
		$ini = $this->preparedIni();
		(new Ini\TypedSource(new \SplFileInfo($ini)))->write(
			['SECTION' => ['foo' => 'bar'], 'SECTION2' => ['bar' => 'foo']]
		);
		Assert::same(
			"[SECTION]\r\nfoo=bar\r\n[SECTION2]\r\nbar=foo\r\n",
			file_get_contents($ini)
		);
	}

	public function testWritingToExistingValues(): void {
		$ini = $this->preparedIni();
		file_put_contents($ini, "me=666\r\nyou=1");
		(new Ini\TypedSource(new \SplFileInfo($ini)))->write(['foo' => 'bar']);
		Assert::same("me=666\r\nyou=1\r\nfoo=bar\r\n", file_get_contents($ini));
	}

	public function testWritingWithoutDuplication(): void {
		$ini = $this->preparedIni();
		file_put_contents($ini, "me=666\r\nyou=1");
		(new Ini\TypedSource(new \SplFileInfo($ini)))->write(['me' => 666]);
		(new Ini\TypedSource(new \SplFileInfo($ini)))->write(['me' => 666]);
		Assert::same("me=666\r\nyou=1\r\n", file_get_contents($ini));
	}

	public function testRemovingByExistingKey(): void {
		$ini = $this->preparedIni();
		file_put_contents($ini, "me=666\r\nyou=1\r\nwe=2");
		(new Ini\TypedSource(new \SplFileInfo($ini)))->remove('you');
		Assert::same("me=666\r\nwe=2\r\n", file_get_contents($ini));
	}

	public function testRemovingByUnknownKey(): void {
		$ini = $this->preparedIni();
		file_put_contents($ini, "me=666\r\nyou=1\r\nwe=2");
		(new Ini\TypedSource(new \SplFileInfo($ini)))->remove('xxxxxxxxxxxxxxxxxxxxxxxxx');
		Assert::same("me=666\r\nyou=1\r\nwe=2\r\n", file_get_contents($ini));
	}

	public function testRemovingByExistingKeyInSection(): void {
		$ini = $this->preparedIni();
		file_put_contents($ini, "foo=666\r\n[SECTION]\r\nfoo=123\r\n");
		(new Ini\TypedSource(new \SplFileInfo($ini)))->remove('foo', 'SECTION');
		Assert::same("foo=666\r\n[SECTION]\r\n", file_get_contents($ini));
	}

	public function testRemovingSameNamedSectionAsKey(): void {
		$ini = $this->preparedIni();
		file_put_contents($ini, "foo=666\r\n[bar]\r\nbar=123\r\n");
		(new Ini\TypedSource(new \SplFileInfo($ini)))->remove('bar', 'bar');
		Assert::same("foo=666\r\n[bar]\r\n", file_get_contents($ini));
	}

	public function testRemovingNullSection(): void {
		$ini = $this->preparedIni();
		file_put_contents($ini, "foo=666\r\n[null]\r\nbar=123\r\n");
		(new Ini\TypedSource(new \SplFileInfo($ini)))->remove('bar', null);
		Assert::same("foo=666\r\n[null]\r\nbar=123\r\n", file_get_contents($ini));
	}

	private function preparedIni() {
		return Tester\FileMock::create('', 'ini');
	}
}

(new TypedSource())->run();
