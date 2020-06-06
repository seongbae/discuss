<?php

namespace Seongbae\Discuss\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Channel extends Model
{
    public function getCssClassesAttribute()
    {
        $classes = config('discuss.channel_classes');

        if (array_key_exists($this->slug, $classes))
            return 'btn '.$classes[$this->slug];
        else
            return 'btn btn-outline-primary btn-sm ';
    }

    public function subscribers()
    {
        return $this->morphedByMany(config('discuss.user_type'), 'user', 'discuss_subscription','item_id','user_id')->where('item_type','channel');
    }

    public function subscribersExcept()
    {
        return $this->subscribers()->where('user_id', '<>', Auth::id());
    }

    public function updateSubscription($user)
    {
        if (! $user->channelSubscriptions->contains($this)) {
            $user->channelSubscriptions()->save($this, ['item_type' => 'channel']);
        } else {
            $user->channelSubscriptions()->detach($this);
        }
    }

    public function subscribe($user)
    {
        if (! $user->channelSubscriptions->contains($this)) {
            $user->channelSubscriptions()->save($this, ['item_type' => 'channel']);
        }
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}
