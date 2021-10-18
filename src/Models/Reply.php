<?php
namespace Seongbae\Discuss\Models;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id',
        'user_type'
    ];

    public function user()
    {
        return $this->morphTo();
    }

    public function getBodyAttribute($value)
    {
        $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        $body = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $value);

        return $body;
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}
