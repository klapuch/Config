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
		$response = new Configuration\CachedSource($origin, 'foo');
		Assert::equal($response->read(), $response->read());
	}

	public function testCachingToApcu() {
		$origin = $this->mock(Configuration\Source::class);
		$data = ['abc'];
		$origin->shouldReceive('read')->once()->andReturn($data);
		Assert::same($data, (new Configuration\CachedSource($origin, 'foo'))->read());
		Assert::count(1, apcu_cache_info()['cache_list']);
		Assert::same($data, apcu_fetch(apcu_cache_info()['cache_list'][0]['info']));
	}

	public function testReadingStoredValueFromApcu() {
		$origin = $this->mock(Configuration\Source::class);
		$origin->shouldReceive('read')->never();
		[$key, $data] = ['config-key', ['abcd']];
		apcu_store(sprintf('klapuch:configuration:%s', $key), $data);
		Assert::same($data, (new Configuration\CachedSource($origin, $key))->read());
		Assert::count(1, apcu_cache_info()['cache_list']);
		Assert::same($data, apcu_fetch(apcu_cache_info()['cache_list'][0]['info']));
	}
}

(new CachedSource())->run();