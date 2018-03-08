<?php
declare(strict_types = 1);
/**
 * @testCase
 * @phpVersion > 7.1.0
 */
namespace Klapuch\Configuration\Unit;

use Klapuch\Configuration;
use Klapuch\Configuration\TestCase;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class CachedSource extends \Tester\TestCase {
	use TestCase\Mockery;

	public function testMultipleCallsWithSingleExecution() {
		$origin = $this->mock(Configuration\Source::class);
		$origin->shouldReceive('read')->once();
		$response = new Configuration\CachedSource($origin);
		Assert::equal($response->read(), $response->read());
	}
}

(new CachedSource())->run();