<?php

namespace Seongbae\Discuss\Traits;

use Seongbae\Discuss\Models\Channel;
use Seongbae\Discuss\Models\Thread;

trait HasThreads
{
    public function threads()
    {
        return $this->morphMany(Thread::class, 'user');
    }

    public function threadSubscriptions()
    {
        return $this->morphToMany(Thread::class, 'user', 'thread_subscription', 'user_id','item_id')
            ->where('item_type', 'thread')
            ->withTimestamps();
    }

    public function channelSubscriptions()
    {
        return $this->morphToMany(Channel::class, 'user', 'thread_subscription', 'user_id','item_id')
            ->where('item_type', 'channel')
            ->withTimestamps();
    }
}
