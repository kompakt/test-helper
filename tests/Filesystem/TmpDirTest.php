<?php

/*
 * This file is part of the kompakt/test-helper package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\TestHelper\Tests\Filesystem;

use PHPUnit\Framework\TestCase;

class TmpDirTest extends TestCase
{
    public function testPrepareSubDirPath()
    {
        $tmpDir = getTmpDir();
        $this->assertRegExp('/\/TmpDirTest$/', $tmpDir->prepareSubDirPath(__CLASS__));
        $this->assertRegExp('/\/testPrepareSubDirPath$/', $tmpDir->prepareSubDirPath(__METHOD__));
        $this->assertRegExp('//', $tmpDir->prepareSubDirPath('+"*ç%&()=^üèöéäà$£'));
    }

    public function testMakeSubDir()
    {
        $tmpDir = getTmpDir();
        $tmpDir->clear();
        $subDir = $tmpDir->prepareSubDirPath(__METHOD__);

        $pathname = $tmpDir->makeSubDir($subDir);
        $this->assertFileExists($pathname);
    }

    public function testDeleteSubDir()
    {
        $tmpDir = getTmpDir();
        $tmpDir->clear();
        $subDir = $tmpDir->prepareSubDirPath(__METHOD__);

        $pathname = $tmpDir->makeSubDir($subDir);
        $this->assertFileExists($pathname);

        $tmpDir->deleteSubDir($subDir);
        $this->assertFalse(is_dir($pathname));
    }

    public function testClear()
    {
        $tmpDir = getTmpDir();
        $tmpDir->clear();
        $subDir = $tmpDir->prepareSubDirPath(__METHOD__);

        $pathname = $tmpDir->makeSubDir($subDir);
        $this->assertFileExists($pathname);

        $tmpDir->clear();
        $this->assertFalse(is_dir($pathname));
    }
}