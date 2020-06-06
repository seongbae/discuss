<?php

namespace Seongbae\Discuss\Tests;

use Orchestra\Testbench\TestCase;
use Seongbae\Discuss\DiscussServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [DiscussServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
