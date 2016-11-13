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

final class Valid extends Tester\TestCase {
	/**
	 * @throws \InvalidArgumentException File "unknownFile.ini" must be readable ini file
	 */
	public function testReadingFromUnknownFile() {
		(new Ini\Valid('unknownFile.ini', new Ini\Fake))->read();
	}

	/**
	 * @throws \InvalidArgumentException File "mock://1.txt" must be readable ini file
	 */
	public function testReadingFromNonIniFile() {
		(new Ini\Valid($this->preparedTxt(), new Ini\Fake))->read();
	}

	/**
	 * @throws \InvalidArgumentException File "unknown.ini" must be writable ini file
	 */
	public function testWritingToUnknownFile() {
		(new Ini\Valid('unknown.ini', new Ini\Fake))->write(['foo' => 'bar']);
	}

	/**
	 * @throws \InvalidArgumentException File "mock://1.txt" must be writable ini file
	 */
	public function testWritingToNonIniFile() {
		(new Ini\Valid($this->preparedTxt(), new Ini\Fake))->write(
			['foo' => 'bar']
		);
	}

	private function preparedTxt() {
		return Tester\FileMock::create('', 'txt');
	}
}

(new Valid)->run();
