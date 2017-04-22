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

final class Valid extends Tester\TestCase {
	/**
	 * @throws \InvalidArgumentException File "unknownFile.ini" must be readable ini file
	 */
	public function testReadingUnknownFile(): void {
		(new Ini\Valid('unknownFile.ini', new Ini\Fake))->read();
	}

	/**
	 * @throws \InvalidArgumentException File "mock://1.txt" must be readable ini file
	 */
	public function testReadingNonIniFile(): void {
		(new Ini\Valid($this->preparedTxt(), new Ini\Fake))->read();
	}

	/**
	 * @throws \InvalidArgumentException File "unknown.ini" must be writable ini file
	 */
	public function testWritingUnknownFile(): void {
		(new Ini\Valid('unknown.ini', new Ini\Fake))->write(['foo' => 'bar']);
	}

	/**
	 * @throws \InvalidArgumentException File "mock://1.txt" must be writable ini file
	 */
	public function testWritingNonIniFile(): void {
		(new Ini\Valid($this->preparedTxt(), new Ini\Fake))->write(
			['foo' => 'bar']
		);
	}

	public function testWritableFile(): void {
		Assert::noError(
			function() {
				(new Ini\Valid($this->preparedIni(), new Ini\Fake))->write([]);
			}
		);
	}

	public function testReadableFile(): void {
		Assert::noError(
			function() {
				(new Ini\Valid($this->preparedIni(), new Ini\Fake))->write([]);
			}
		);
	}

	public function testCaseInsensitiveExtension(): void {
		Assert::noError(
			function() {
				(new Ini\Valid(
					Tester\FileMock::create('', 'iNi'),
					new Ini\Fake
				))->write([]);
			}
		);
		Assert::noError(
			function() {
				(new Ini\Valid(
					Tester\FileMock::create('', 'iNi'),
					new Ini\Fake
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

(new Valid)->run();