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

final class ValidSource extends Tester\TestCase {
	/**
	 * @throws \InvalidArgumentException File "unknownFile.ini" must be readable ini file
	 */
	public function testReadingUnknownFile(): void {
		(new Ini\ValidSource(new \SplFileInfo('unknownFile.ini'), new Ini\FakeSource))->read();
	}

	/**
	 * @throws \InvalidArgumentException File "mock://1.txt" must be readable ini file
	 */
	public function testReadingNonIniFile(): void {
		(new Ini\ValidSource(new \SplFileInfo($this->preparedTxt()), new Ini\FakeSource))->read();
	}

	/**
	 * @throws \InvalidArgumentException File "unknown.ini" must be writable ini file
	 */
	public function testWritingUnknownFile(): void {
		(new Ini\ValidSource(new \SplFileInfo('unknown.ini'), new Ini\FakeSource))->write(['foo' => 'bar']);
	}

	/**
	 * @throws \InvalidArgumentException File "mock://1.txt" must be writable ini file
	 */
	public function testWritingNonIniFile(): void {
		(new Ini\ValidSource(new \SplFileInfo($this->preparedTxt()), new Ini\FakeSource))->write(
			['foo' => 'bar']
		);
	}

	public function testWritableFile(): void {
		Assert::noError(
			function() {
				(new Ini\ValidSource(
					new \SplFileInfo($this->preparedIni()),
					new Ini\FakeSource
				))->write([]);
			}
		);
	}

	public function testReadableFile(): void {
		Assert::noError(
			function() {
				(new Ini\ValidSource(
					new \SplFileInfo($this->preparedIni()),
					new Ini\FakeSource
				))->write([]);
			}
		);
	}

	public function testCaseInsensitiveExtension(): void {
		Assert::noError(
			function() {
				(new Ini\ValidSource(
					new \SplFileInfo(Tester\FileMock::create('', 'iNi')),
					new Ini\FakeSource
				))->write([]);
			}
		);
		Assert::noError(
			function() {
				(new Ini\ValidSource(
					new \SplFileInfo(Tester\FileMock::create('', 'iNi')),
					new Ini\FakeSource
				))->read();
			}
		);
	}

	private function preparedTxt() {
		return Tester\FileMock::create('', 'txt');
	}

	private function preparedIni() {
		return Tester\FileMock::create('', 'ini');
	}
}

(new ValidSource)->run();