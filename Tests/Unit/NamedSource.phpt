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

final class NamedSource extends Tester\TestCase {
	public function testAddingNamedKey() {
		Assert::same(
			[
				'features' => [
					'age' => 20,
					'id' => '1abc',
				],
			],
			(new Configuration\NamedSource(
				'features',
				new Configuration\FakeSource(['age' => 20, 'id' => '1abc'])
			))->read()
		);
	}
}

(new NamedSource)->run();