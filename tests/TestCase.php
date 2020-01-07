<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\ServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
