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

    protected $table = 'discuss_threads';

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

    public function getProcessedBodyAttribute()
    {
        $body = $this->body;

        // If iframe already exists, make it responsive and return (no URL conversion needed)
        if (preg_match('/<iframe[^>]*>/i', $body)) {
            // Remove fixed width/height attributes and wrap in responsive container
            $body = preg_replace_callback('/<iframe([^>]*)>/i', function($matches) {
                $attrs = $matches[1];
                // Remove width and height attributes
                $attrs = preg_replace('/\s*(?:width|height)\s*=\s*["\'][^"\']*["\']/i', '', $attrs);
                // Add responsive styles
                return '<iframe' . $attrs . ' style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;">';
            }, $body);

            // Wrap iframe in responsive container if not already wrapped
            if (strpos($body, 'padding-bottom') === false) {
                $body = preg_replace(
                    '/(<iframe[^>]*>.*?<\/iframe>)/is',
                    "<div style='position: relative;padding-bottom: 56.25%;padding-top: 25px;height: 0;'>$1</div>",
                    $body
                );
            }

            return $body;
        }

        // Important: Convert YouTube URLs to iframes first
        // Convert YouTube URLs to iframe (preserve line breaks)
        if (strpos($body, 'youtube.com') !== false || strpos($body, 'youtu.be') !== false) {
            $body = preg_replace(
                "/(https?:\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([a-zA-Z0-9\-_]+)[^\s]*)/i",
                "\n\n<div style='position: relative;padding-bottom: 56.25%;padding-top: 25px;height: 0;'><iframe src=\"//www.youtube.com/embed/$2\" allowfullscreen style='position: absolute;top: 0;left: 0;width: 100%;height: 100%;'></iframe></div>\n\n",
                $body
            );
        }

        // Protect HTML blocks with placeholders
        $htmlProtected = [];
        $index = 0;
        $body = preg_replace_callback('/<div[^>]*>.*?<\/div>/is', function($m) use (&$htmlProtected, &$index) {
            $key = '___HTML_' . $index . '___';
            $htmlProtected[$key] = $m[0];
            $index++;
            return $key;
        }, $body);

        // Convert general URLs to links (only URLs starting with http:// or https://)
        $body = preg_replace('@(https?://[^\s<>]+)@', '<a href="$1" target="_blank" title="$1">$1</a>', $body);

        // Restore HTML blocks
        foreach ($htmlProtected as $key => $html) {
            $body = str_replace($key, $html, $body);
        }

        return $body;
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
