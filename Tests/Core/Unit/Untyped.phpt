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

final class Untyped extends Tester\TestCase {
	public function testCorrectTypes() {
		$ini = new Ini\Fake(
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
			(new Ini\Untyped($ini))->read()
		);
	}
}

(new Untyped())->run();
