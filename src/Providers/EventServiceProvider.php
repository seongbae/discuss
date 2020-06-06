<?php

namespace Seongbae\Discuss\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Seongbae\Discuss\Events\NewThread;
use Seongbae\Discuss\Events\NewReply;
use Seongbae\Discuss\Listeners\NotifyChannelSubscribers;
use Seongbae\Discuss\Listeners\NotifyThreadSubscribers;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        NewThread::class => [
            NotifyChannelSubscribers::class,
        ],
        NewReply::class => [
            NotifyThreadSubscribers::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
