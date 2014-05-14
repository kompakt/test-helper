<?php

/*
 * This file is part of the kompakt/test-helper package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Tests\TestHelper\Filesystem;

use Kompakt\TestHelper\Filesystem\TmpDir;

class TmpDirTest extends \PHPUnit_Framework_TestCase
{
    public function testMakeSubDir()
    {
        $tmpDir = new TmpDir(TESTS_KOMPAKT_TESTHELPER_TEMP_DIR);
        $subDir = TESTS_KOMPAKT_TESTHELPER_TEMP_DIR . '/sub-dir';

        if (is_dir($subDir))
        {
            rmdir($subDir);
        }

        $tmpDir->makeSubDir($subDir);
        $this->assertFileExists($subDir);
    }
}