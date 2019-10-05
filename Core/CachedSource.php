<?php
declare(strict_types = 1);
namespace Klapuch\Configuration;

/**
 * Cached source
 */
final class CachedSource implements Source {
	private const FILENAME = 'klapuch_configuration.php';

	private $origin;
	private $directory;

	public function __construct(Source $origin, \SplFileInfo $directory) {
		$this->origin = $origin;
		$this->directory = $directory;
	}

	public function read(): array {
		$file = new \SplFileInfo($this->directory->getPathname() . DIRECTORY_SEPARATOR . self::FILENAME);
		if (!$file->isFile()) {
			$lock = sprintf('%s.lock', $file->getPathname());
			$handle = fopen($lock, 'c+');
			if ($handle === false || !flock($handle, LOCK_EX)) {
				throw new \RuntimeException(\sprintf('Unable to create or acquire exclusive lock on file "%s".', $lock));
			}
			if (!$file->isFile()) {
				$temp = sprintf('%s.temp', $file->getPathname());
				if (!@file_put_contents($temp, $this->content())) {
					throw new \RuntimeException(sprintf('Can not write to file "%s"', $temp));
				}
				rename($temp, $file->getPathname()); // atomic replace
				if (function_exists('opcache_invalidate')) {
					opcache_invalidate($file->getPathname(), true);
				}
			}
			flock($handle, LOCK_UN);
			fclose($handle);
			@unlink($lock); // intentionally @ - file may become locked on Windows
		}
		return require $file->getPathname();
	}

	private function content(): string
	{
		return sprintf('<?php return %s;', var_export($this->origin->read(), true));
	}
}