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

final class Muted extends Tester\TestCase {
	public function testReadingNothingOnThrownError() {
		Assert::same(
			[],
			(new Ini\Muted(
				new Ini\Valid('foo.bar', new Ini\Typed('foo.bar'))
			))->read()
		);
	}

	public function testReadingKnownFileWithContent() {
		$file = Tester\FileMock::create('foo=bar', 'ini');
		Assert::same(
			['foo' => 'bar'],
			(new Ini\Muted(
				new Ini\Valid($file, new Ini\Typed($file))
			))->read()
		);
	}

	public function testWritingNothingOnThrownError() {
		Assert::noError(function() {
			(new Ini\Muted(
				new Ini\Valid('foo.bar', new Ini\Typed('foo.bar'))
			))->write(['foo']);
		});
	}

	public function testRemovingNothingOnThrownError() {
		Assert::noError(function() {
			(new Ini\Muted(
				new Ini\Valid('foo.bar', new Ini\Typed('foo.bar'))
			))->remove('foo', 'bar');
		});
	}
}

(new Muted())->run();