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

final class MutedSource extends Tester\TestCase {
	public function testReadingNothingOnThrownError(): void {
		Assert::same(
			[],
			(new Ini\MutedSource(
				new Ini\ValidSource('foo.bar', new Ini\TypedSource('foo.bar'))
			))->read()
		);
	}

	public function testReadingKnownFileWithContent(): void {
		$file = Tester\FileMock::create('foo=bar', 'ini');
		Assert::same(
			['foo' => 'bar'],
			(new Ini\MutedSource(
				new Ini\ValidSource($file, new Ini\TypedSource($file))
			))->read()
		);
	}

	public function testWritingNothingOnThrownError(): void {
		Assert::noError(function() {
			(new Ini\MutedSource(
				new Ini\ValidSource('foo.bar', new Ini\TypedSource('foo.bar'))
			))->write(['foo']);
		});
	}

	public function testRemovingNothingOnThrownError(): void {
		Assert::noError(function() {
			(new Ini\MutedSource(
				new Ini\ValidSource('foo.bar', new Ini\TypedSource('foo.bar'))
			))->remove('foo', 'bar');
		});
	}
}

(new MutedSource())->run();