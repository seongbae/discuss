<?php

namespace Seongbae\Discuss\Traits;

use Seongbae\Discuss\Models\Channel;
use Seongbae\Discuss\Models\Subscription;
use Seongbae\Discuss\Models\Thread;

trait HasThreads
{
    public function threads()
    {
        return $this->morphMany(Thread::class, 'user');
    }

    public function subscribedTo($subscribable)
    {
        return Subscription::where('user_id', $this->id)
                ->where('subscribable_id', $subscribable->id)
                ->where('subscribable_type', get_class($subscribable))
                ->count() > 0;
    }

    public function threadSubscriptions()
    {
        return $this->morphToMany(Thread::class, 'user', 'discuss_subscription', 'user_id','subscribable_id')
            ->where('subscribable_type', Thread::class)
            ->withTimestamps();
    }

    public function channelSubscriptions()
    {
        return $this->morphToMany(Channel::class, 'user', 'discuss_subscription', 'user_id','subscribable_id')
            ->where('subscribable_type', Channel::class)
            ->withTimestamps();
    }
}
