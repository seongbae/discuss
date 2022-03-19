<?php

namespace Seongbae\Discuss\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Channel extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    protected $table = 'discuss_channels';

    public $timestamps = false;

    public function getCssClassesAttribute()
    {
        $classes = config('discuss.channel_classes');

        if (array_key_exists($this->slug, $classes))
            return 'btn '.$classes[$this->slug];
        else
            return 'btn btn-outline-primary btn-sm ';
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscribable_id')->where('subscribable_type', Thread::class);
    }

    public function subscribers()
    {
        return $this->morphedByMany(config('discuss.user_type'), 'user', 'discuss_subscription', 'subscribable_id');
    }

    public function subscribersExcept()
    {
        return $this->subscribers()->where('user_id', '<>', Auth::id());
    }

    public function attachSubscriber($user)
    {
        if (!Subscription::where('user_id', $user->id)
            ->where('subscribable_type', Channel::class)
            ->where('subscribable_id', $this->id)
            ->exists())
            Subscription::create(['user_id'=>$user->id, 'user_type'=>config('discuss.user_type'), 'subscribable_type'=>Channel::class, 'subscribable_id'=>$this->id]);

    }

    public function detachSubscriber($user)
    {
        $subscription = Subscription::where('user_id', $user->id)->where('subscribable_type', Channel::class)->where('subscribable_id', $this->id);

        if ($subscription)
            $subscription->delete();
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
