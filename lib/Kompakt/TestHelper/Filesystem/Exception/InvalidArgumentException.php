<?php

/*
 * This file is part of the kompakt/test-helper package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\TestHelper\Filesystem\Exception;

use Kompakt\TestHelper\Exception as BaseException;

class InvalidArgumentException extends \InvalidArgumentException implements BaseException
{}