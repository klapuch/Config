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

final class CombinedSource extends Tester\TestCase {
	public function testCombiningFlatWithPrioritizingLast(): void {
		Assert::same(
			['foo' => 'bar'],
			(new Ini\CombinedSource(
				new Ini\FakeSource(['foo' => 'baz']),
				new Ini\FakeSource(['foo' => 'bax']),
				new Ini\FakeSource(['foo' => 'bar'])
			))->read()
		);
	}

	public function testNotAffectingOtherDuringCombining(): void {
		Assert::same(
			['foo' => 'bar', 'bar' => 'baz', 'how' => 'good'],
			(new Ini\CombinedSource(
				new Ini\FakeSource(['foo' => 'baz']),
				new Ini\FakeSource(['bar' => 'baz']),
				new Ini\FakeSource(['foo' => 'bar', 'how' => 'good'])
			))->read()
		);
	}

	public function testCombiningSectionsWithMerge(): void {
		Assert::same(
			['section' => ['foo' => 'bar', 'how' => 'good']],
			(new Ini\CombinedSource(
				new Ini\FakeSource(['section' => ['foo' => 'baz']]),
				new Ini\FakeSource(['section' => ['foo' => 'bax', 'how' => 'good']]),
				new Ini\FakeSource(['section' => ['foo' => 'bar']])
			))->read()
		);
	}
}

(new CombinedSource())->run();