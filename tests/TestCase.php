<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    private static $setupInvoked = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseUrl = config('app.url');

        if (!$this::$setupInvoked) {
            $this->artisan('migrate:fresh --seed');
            $this::$setupInvoked = true;
        }
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
