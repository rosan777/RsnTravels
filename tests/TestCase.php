<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // - artisan calls in tests run for real (no Mockery needed)
    public $mockConsoleOutput = false;
}
