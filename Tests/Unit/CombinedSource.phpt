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

final class CombinedSource extends Tester\TestCase {
	public function testCombiningFlatWithPrioritizingLast(): void {
		Assert::same(
			['foo' => 'bar'],
			(new Configuration\CombinedSource(
				new Configuration\FakeSource(['foo' => 'baz']),
				new Configuration\FakeSource(['foo' => 'bax']),
				new Configuration\FakeSource(['foo' => 'bar'])
			))->read()
		);
	}

	public function testNotAffectingOtherDuringCombining(): void {
		Assert::same(
			['foo' => 'bar', 'bar' => 'baz', 'how' => 'good'],
			(new Configuration\CombinedSource(
				new Configuration\FakeSource(['foo' => 'baz']),
				new Configuration\FakeSource(['bar' => 'baz']),
				new Configuration\FakeSource(['foo' => 'bar', 'how' => 'good'])
			))->read()
		);
	}

	public function testCombiningSectionsWithMerge(): void {
		Assert::same(
			['section' => ['foo' => 'bar', 'how' => 'good']],
			(new Configuration\CombinedSource(
				new Configuration\FakeSource(['section' => ['foo' => 'baz']]),
				new Configuration\FakeSource(['section' => ['foo' => 'bax', 'how' => 'good']]),
				new Configuration\FakeSource(['section' => ['foo' => 'bar']])
			))->read()
		);
	}
}

(new CombinedSource())->run();