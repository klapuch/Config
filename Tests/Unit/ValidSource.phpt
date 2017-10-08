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
	 * @throws \UnexpectedValueException File "unknownFile.ini" is not in ini format or does not exist
	 */
	public function testThrowingOnUnknownFile() {
		(new Ini\ValidSource(new \SplFileInfo('unknownFile.ini')))->read();
	}

	/**
	 * @throws \UnexpectedValueException File "mock://1.ini" is not in ini format or does not exist
	 */
	public function testThrowingOnBadFormat() {
		(new Ini\ValidSource(new \SplFileInfo(Tester\FileMock::create('!', 'ini'))))->read();
	}

	public function testCaseInsensitiveExtension() {
		Assert::noError(
			function() {
				(new Ini\ValidSource(
					new \SplFileInfo(Tester\FileMock::create('', 'iNi'))
				))->read();
			}
		);
	}

	public function testReadingWithSections() {
		$ini = Tester\FileMock::create('', 'ini');
		file_put_contents($ini, '[SECTION]foo = bar');
		Assert::same(
			['SECTION' => ['foo' => 'bar']],
			(new Ini\ValidSource(new \SplFileInfo($ini)))->read()
		);
	}

	public function testCastedTypes() {
		$ini = Tester\FileMock::create('', 'ini');
		file_put_contents(
			$ini,
			"number=666\r\ntext=some string\r\nbool=true\r\n10=2"
		);
		Assert::same(
			['number' => 666, 'text' => 'some string', 'bool' => true, 10 => 2],
			(new Ini\ValidSource(new \SplFileInfo($ini)))->read()
		);
	}
}

(new ValidSource)->run();