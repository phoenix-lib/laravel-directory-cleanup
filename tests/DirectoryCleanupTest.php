<?php

namespace Spatie\DirectoryCleanup\Test;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;

class DirectoryCleanupTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $tempDirectory = $this->getTempDirectory();
        $now = time();

        touch("{$tempDirectory}1/file1.txt", ($now - 660));
        touch("{$tempDirectory}1/file2.txt", $now);
        touch("{$tempDirectory}2/file1.txt", $now - 310);
        touch("{$tempDirectory}2/file2.txt", $now);
    }

    /** @test */
    public function it_can_cleanup_the_directories_specified_in_the_config_file()
    {
        $tempDirectory = $this->getTempDirectory();

        $this->assertEquals(2, count(app(Filesystem::class)->files("{$tempDirectory}1")));
        $this->assertEquals(2, count(app(Filesystem::class)->files("{$tempDirectory}2")));

        $this->app->make(Kernel::class)->call('clean:directories');

        $this->assertEquals(1, count(app(Filesystem::class)->files("{$tempDirectory}1")));
        $this->assertEquals(1, count(app(Filesystem::class)->files("{$tempDirectory}2")));
    }

    protected function getTempDirectory()
    {
        return __DIR__.'/temp';
    }
}
