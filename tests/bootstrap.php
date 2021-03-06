<?php

/*
 * This file is part of the kompakt/test-helper package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

// load testing configuration
require_once (file_exists(__DIR__ . '/config.php')) ? 'config.php' : 'config.php.dist';

// autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';


use Kompakt\TestHelper\Filesystem\TmpDir;

function getTmpDir()
{
    return new TmpDir(TESTS_KOMPAKT_TESTHELPER_TEMP_DIR, 'Kompakt\TestHelper\Tests');
}