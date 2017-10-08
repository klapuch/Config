<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.1.0
 */
namespace Klapuch\Ini\Unit;

use Klapuch\Ini;
use Klapuch\Ini\TestCase;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class CachedSource extends \Tester\TestCase {
	use TestCase\Mockery;

	public function testMultipleCallsWithSingleExecution() {
		$origin = $this->mock(Ini\Source::class);
		$origin->shouldReceive('read')->once();
		$response = new Ini\CachedSource($origin);
		Assert::equal($response->read(), $response->read());
	}
}

(new CachedSource())->run();