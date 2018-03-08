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

	public function testCachingToApcu() {
		$origin = $this->mock(Configuration\Source::class);
		$origin->shouldReceive('read')->once()->andReturn(['abc']);
		Assert::same(['abc'], (new Configuration\CachedSource($origin))->read());
		Assert::count(1, apcu_cache_info()['cache_list']);
		Assert::same(['abc'], apcu_fetch(apcu_cache_info()['cache_list'][0]['info']));
	}

	public function testReadingStoredValueFromApcu() {
		$origin = $this->mock(Configuration\Source::class);
		$origin->shouldReceive('read')->never();
		apcu_store(spl_object_hash($origin), ['abcd']);
		Assert::same(['abcd'], (new Configuration\CachedSource($origin))->read());
		Assert::count(1, apcu_cache_info()['cache_list']);
		Assert::same(['abcd'], apcu_fetch(apcu_cache_info()['cache_list'][0]['info']));
	}
}

(new CachedSource())->run();