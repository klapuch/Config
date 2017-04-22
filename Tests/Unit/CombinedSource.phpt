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

	public function testNotAffectingOtherDuringCombibing(): void {
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

	public function testWritingToAll(): void {
		$a = new Ini\FakeSource(['a' => 'baz']);
		$b = new Ini\FakeSource(['b' => 'bax']);
		$c = new Ini\FakeSource(['c' => 'bar']);
		(new Ini\CombinedSource($a, $b, $c))->write(['how' => 'good']);
		Assert::same(['a' => 'baz', 'how' => 'good'], $a->read());
		Assert::same(['b' => 'bax', 'how' => 'good'], $b->read());
		Assert::same(['c' => 'bar', 'how' => 'good'], $c->read());
	}

	public function testRemovingFromAll(): void {
		$a = new Ini\FakeSource(['a' => 'baz', 'how' => 'good']);
		$b = new Ini\FakeSource(['b' => 'bax', 'how' => 'good']);
		$c = new Ini\FakeSource(['c' => 'bar', 'how' => 'good']);
		(new Ini\CombinedSource($a, $b, $c))->remove('how');
		Assert::same(['a' => 'baz'], $a->read());
		Assert::same(['b' => 'bax'], $b->read());
		Assert::same(['c' => 'bar'], $c->read());
	}
}

(new CombinedSource())->run();