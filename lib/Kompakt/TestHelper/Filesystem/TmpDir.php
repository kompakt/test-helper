<?php

/*
 * This file is part of the kompakt/test-helper package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\TestHelper\Filesystem;

use Kompakt\TestHelper\Filesystem\Exception\InvalidArgumentException;

class TmpDir
{
    protected $tmpDir = null;
    protected $illegalCharsPattern = '/[^a-z0-9\.\-\/_]/i';

    public function __construct($tmpDir)
    {
        $info = new \SplFileInfo($tmpDir);

        if (!$info->isDir())
        {
            throw new InvalidArgumentException(sprintf('Temporary dir not found: "%s"', $tmpDir));
        }

        if (!$info->isReadable())
        {
            throw new InvalidArgumentException(sprintf('Temporary dir not readable: "%s"', $tmpDir));
        }

        if (!$info->isWritable())
        {
            throw new InvalidArgumentException(sprintf('Temporary dir not writable: "%s"', $tmpDir));
        }

        $this->tmpDir = $tmpDir;
    }

    public function preparePathname($subDirPart)
    {
        $subDirPart = preg_replace('/::/', '/', $subDirPart); // if used with __METHOD__
        $subDirPart = preg_replace('/\\\/', '/', $subDirPart); // if used with __CLASS__
        $subDirPart = preg_replace($this->illegalCharsPattern, '', $subDirPart); // remove illegal stuff
        return sprintf('%s/%s', $this->tmpDir, $subDirPart);
    }

    public function replaceSubDir($pathname)
    {
        $this->deleteSubDir($pathname);
        $this->makeSubDir($pathname);
    }

    public function makeSubDir($pathname)
    {
        if (!$this->isValidSubDir($pathname))
        {
            throw new InvalidArgumentException(sprintf('Not a valid subdir of temporary dir: "%s"', $pathname));
        }

        $fileInfo = new \SplFileInfo($pathname);

        if (!$fileInfo->isDir())
        {
            mkdir($pathname, 0777, true);
        }

        return $pathname;
    }

    public function deleteSubDir($pathname)
    {
        if (!$this->isValidSubDir($pathname))
        {
            throw new InvalidArgumentException(sprintf('Not a valid subdir of temporary dir: "%s"', $pathname));
        }

        $fileInfo = new \SplFileInfo($pathname);

        if (!$fileInfo->isDir() || !$fileInfo->isReadable() || !$fileInfo->isWritable())
        {
            return;
        }

        foreach (new \DirectoryIterator($pathname) as $fileInfo)
        {
            if ($fileInfo->isDot())
            {
                continue;
            }

            if ($fileInfo->isDir())
            {
                $this->deleteSubDir($fileInfo->getPathname());
            }
            else {
                unlink($fileInfo->getPathname());
            }
        }

        rmdir($pathname);
    }

    public function clear(array $exceptions = array('.gitkeep'))
    {
        foreach (new \DirectoryIterator($this->tmpDir) as $fileInfo)
        {
            if ($fileInfo->isDot())
            {
                continue;
            }

            if (in_array($fileInfo->getFilename(), $exceptions))
            {
                continue;
            }

            if ($fileInfo->isDir())
            {
                $this->deleteSubDir($fileInfo->getPathname());
            }
            else {
                unlink($fileInfo->getPathname());
            }
        }
    }

    public function isValidSubDir($pathname)
    {
        $pathname = rtrim($pathname, '/');
        $tmpDir = rtrim($this->tmpDir, '/');
        $find = sprintf('/^%s/', str_replace('/', '\/', $tmpDir));

        if ($pathname === $tmpDir)
        {
            return false;
        }

        if (!preg_match($find, $pathname))
        {
            return false;
        }

        if (preg_match($this->illegalCharsPattern, $pathname))
        {
            return false;
        }

        return true;
    }
}