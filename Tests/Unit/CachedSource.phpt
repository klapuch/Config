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
use Tester\Environment;

require __DIR__ . '/../bootstrap.php';

final class CachedSource extends \Tester\TestCase {
	use TestCase\Mockery;

	protected function setUp() {
		parent::setUp();
		Environment::lock(__CLASS__, __DIR__ . '/../temp');
	}

	public function testMultipleCallsWithSingleExecution() {
		$origin = $this->mock(Configuration\Source::class);
		$origin->shouldReceive('read')->once();
		$response = new Configuration\CachedSource($origin, new \SplFileInfo(__DIR__ . '/../temp'));
		Assert::equal($response->read(), $response->read());
	}

	public function testFormatOfCreatedFile() {
		$origin = $this->mock(Configuration\Source::class);
		$origin->shouldReceive('read')->once()->andReturn(['username' => 'myself']);
		$response = new Configuration\CachedSource($origin, new \SplFileInfo(__DIR__ . '/../temp'));
		Assert::equal(['username' => 'myself'], $response->read());
		Assert::same(sprintf('<?php return %s;', var_export(['username' => 'myself'], true)), file_get_contents(__DIR__ . '/../temp/klapuch_configuration.php'));
	}

	protected function tearDown(): void {
		parent::tearDown();
		@unlink(__DIR__ . '/../temp/klapuch_configuration.php');
	}
}

(new CachedSource())->run();