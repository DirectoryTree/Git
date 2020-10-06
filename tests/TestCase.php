<?php

namespace DirectoryTree\Git\Tests;

use TitasGailius\Terminal\Terminal;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        Terminal::reset();
    }
}
