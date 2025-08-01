<?php

namespace Seongbae\Discuss\Traits;

use Seongbae\Discuss\Models\Channel;
use Seongbae\Discuss\Models\Subscription;
use Seongbae\Discuss\Models\Thread;

trait HasThreads
{
    public function discussThreads()
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

    public function canManageDiscuss()
    {
        if (in_array($this->id, config('discuss.admin_user_ids'))
            || in_array($this->email, config('discuss.admin_user_emails')))
            return true;

        if (count(config('discuss.admin_user_roles')) > 0 &&
            $this->hasRole([config('discuss.admin_user_roles')]))
            return true;

        return false;
    }
}
