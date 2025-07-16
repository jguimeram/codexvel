<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Disable Vite asset loading during tests to avoid requiring
        // a pre-built manifest file.
        $this->withoutVite();
    }
}
