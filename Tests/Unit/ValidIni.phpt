<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.1.0
 */
namespace Klapuch\Configuration\Unit;

use Klapuch\Configuration;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class ValidIni extends Tester\TestCase {
	/**
	 * @throws \UnexpectedValueException File "unknownFile.ini" is not in ini format or does not exist
	 */
	public function testThrowingOnUnknownFile() {
		(new Configuration\ValidIni(new \SplFileInfo('unknownFile.ini')))->read();
	}

	/**
	 * @throws \UnexpectedValueException File "mock://1.ini" is not in ini format or does not exist
	 */
	public function testThrowingOnBadFormat() {
		(new Configuration\ValidIni(new \SplFileInfo(Tester\FileMock::create('!', 'ini'))))->read();
	}

	public function testCaseInsensitiveExtension() {
		Assert::noError(
			function() {
				(new Configuration\ValidIni(
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
			(new Configuration\ValidIni(new \SplFileInfo($ini)))->read()
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
			(new Configuration\ValidIni(new \SplFileInfo($ini)))->read()
		);
	}
}

(new ValidIni)->run();