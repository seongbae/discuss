<?php
namespace Seongbae\Discuss\Models;

use Illuminate\Database\Eloquent\Model;
use Seongbae\Discuss\Events\NewReply;
use Seongbae\Discuss\Events\NewThread;
use Auth;

class Subscription extends Model
{
    protected $table = 'discuss_subscription';

    protected $fillable = [
        'subscribable_id',
        'subscribable_type',
        'user_id',
        'user_type'
    ];

	public function subscribable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(config('discuss.user_type'));
    }

    public function threads()
    {
        return $this->morphedByMany(Thread::class, 'subscribable');
    }

    /**
     * Get all of the videos that are assigned this tag.
     */
    public function channels()
    {
        return $this->morphedByMany(Channel::class, 'subscribable');
    }
}
