<?php

namespace Seongbae\Discuss\Tests;

use Orchestra\Testbench\TestCase;
use Seongbae\Discuss\DiscussServiceProvider;

class ThreadTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [DiscussServiceProvider::class];
    }

    /** @test */
    public function test_thread_detail_can_be_retrieved()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
