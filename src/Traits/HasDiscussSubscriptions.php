<?php

namespace Seongbae\Discuss\Traits;

use Seongbae\Discuss\Models\Channel;
use Seongbae\Discuss\Models\Thread;

trait HasDiscussSubscriptions
{
    public function threadSubscriptions()
    {
        return $this->morphToMany(Thread::class, 'user', 'discuss_subscription', 'user_id','item_id')
            ->where('item_type', 'thread')
            ->withTimestamps();
    }

    public function channelSubscriptions()
    {
        return $this->morphToMany(Channel::class, 'user', 'discuss_subscription', 'user_id','item_id')
            ->where('item_type', 'channel')
            ->withTimestamps();
    }
}
