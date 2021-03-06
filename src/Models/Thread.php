<?php
namespace Seongbae\Discuss\Models;

use Illuminate\Database\Eloquent\Model;
use Seongbae\Discuss\Events\NewReply;
use Seongbae\Discuss\Events\NewThread;
use Auth;

class Thread extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'channel_id',
        'title',
        'body',
        'view_count',
        'slug'
    ];

    protected $guarded = [];

    protected $with = ['user', 'channel'];

    protected $appends = ['created_at_human_readable'];

    /**
     * Fetch a path to the current thread.
     *
     * @return string
     */
    public function path()
    {
        return '/discuss/' . $this->channel->slug . '/'.$this->slug;
    }

    public function getCreatedAtHumanReadableAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * A thread belongs to a creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->morphTo();
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

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Add a reply to the thread.
     *
     * @param $reply
     */
    public function addReply($reply, $subscribe=true)
    {
        $reply = $this->replies()->create($reply);

        event(new NewReply($reply));

        if ($subscribe)
            $this->attachSubscriber($reply->user);
    }

    public function attachSubscriber($user)
    {
        if (!Subscription::where('user_id', $user->id)
            ->where('subscribable_type', Thread::class)
            ->where('subscribable_id', $this->id)
            ->exists())
            Subscription::create(['user_id'=>$user->id, 'user_type'=>config('discuss.user_type'), 'subscribable_type'=>Thread::class, 'subscribable_id'=>$this->id]);
    }

    public function detachSubscriber($user)
    {
        $subscription = Subscription::where('user_id', $user->id)->where('subscribable_type', Thread::class)->where('subscribable_id', $this->id)->first();

        if ($subscription)
            $subscription->delete();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function($builder) {
            $builder->withCount('replies');
        });

        static::created(function ($thread) {
            event(new NewThread($thread));
        });

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });
    }
}
