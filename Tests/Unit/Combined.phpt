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

final class Combined extends Tester\TestCase {
	public function testCombiningFlatWithPrioritizingLast(): void {
		Assert::same(
			['foo' => 'bar'],
			(new Ini\Combined(
				new Ini\Fake(['foo' => 'baz']),
				new Ini\Fake(['foo' => 'bax']),
				new Ini\Fake(['foo' => 'bar'])
			))->read()
		);
	}

	public function testNotAffectingOtherDuringCombibing(): void {
		Assert::same(
			['foo' => 'bar', 'bar' => 'baz', 'how' => 'good'],
			(new Ini\Combined(
				new Ini\Fake(['foo' => 'baz']),
				new Ini\Fake(['bar' => 'baz']),
				new Ini\Fake(['foo' => 'bar', 'how' => 'good'])
			))->read()
		);
	}

	public function testCombiningSectionsWithMerge(): void {
		Assert::same(
			['section' => ['foo' => 'bar', 'how' => 'good']],
			(new Ini\Combined(
				new Ini\Fake(['section' => ['foo' => 'baz']]),
				new Ini\Fake(['section' => ['foo' => 'bax', 'how' => 'good']]),
				new Ini\Fake(['section' => ['foo' => 'bar']])
			))->read()
		);
	}

	public function testWritingToAll(): void {
		$a = new Ini\Fake(['a' => 'baz']);
		$b = new Ini\Fake(['b' => 'bax']);
		$c = new Ini\Fake(['c' => 'bar']);
		(new Ini\Combined($a, $b, $c))->write(['how' => 'good']);
		Assert::same(['a' => 'baz', 'how' => 'good'], $a->read());
		Assert::same(['b' => 'bax', 'how' => 'good'], $b->read());
		Assert::same(['c' => 'bar', 'how' => 'good'], $c->read());
	}

	public function testRemovingFromAll(): void {
		$a = new Ini\Fake(['a' => 'baz', 'how' => 'good']);
		$b = new Ini\Fake(['b' => 'bax', 'how' => 'good']);
		$c = new Ini\Fake(['c' => 'bar', 'how' => 'good']);
		(new Ini\Combined($a, $b, $c))->remove('how');
		Assert::same(['a' => 'baz'], $a->read());
		Assert::same(['b' => 'bax'], $b->read());
		Assert::same(['c' => 'bar'], $c->read());
	}
}

(new Combined())->run();