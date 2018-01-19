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

final class ValidJson extends Tester\TestCase {
	/**
	 * @throws \UnexpectedValueException File "unknownFile.json" does not exist
	 */
	public function testThrowingOnUnknownFile() {
		(new Configuration\ValidJson(new \SplFileInfo('unknownFile.json')))->read();
	}

	/**
	 * @throws \UnexpectedValueException File "mock://1.json" is not in json format
	 */
	public function testThrowingOnBadFormat() {
		(new Configuration\ValidJson(new \SplFileInfo(Tester\FileMock::create('!', 'json'))))->read();
	}

	public function testCaseInsensitiveExtension() {
		Assert::noError(
			function() {
				(new Configuration\ValidJson(
					new \SplFileInfo(Tester\FileMock::create('{}', 'jSoN'))
				))->read();
			}
		);
	}

	public function testSuccessfulReading() {
		$json = Tester\FileMock::create('{"section": {"foo": "bar"}}', 'json');
		Assert::same(
			['section' => ['foo' => 'bar']],
			(new Configuration\ValidJson(new \SplFileInfo($json)))->read()
		);
	}

	public function testCastedTypes() {
		$json = Tester\FileMock::create('{"section": {"foo": 66, "bar": 0.5}}', 'json');
		Assert::same(
			['section' => ['foo' => 66, 'bar' => 0.5]],
			(new Configuration\ValidJson(new \SplFileInfo($json)))->read()
		);
	}
}

(new ValidJson)->run();