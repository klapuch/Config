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

final class UntypedSource extends Tester\TestCase {
	public function testCorrectTypes(): void {
		$ini = new Ini\FakeSource(
			[
				'number' => 666,
				'text' => 'some string',
				'boolean' => true,
				10 => 2,
			]
		);
		Assert::same(
			[
				'number' => '666',
				'text' => 'some string',
				'boolean' => '1',
				10 => '2',
			],
			(new Ini\UntypedSource($ini))->read()
		);
	}
}

(new UntypedSource())->run();
