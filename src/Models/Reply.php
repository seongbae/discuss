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

    protected $table = 'discuss_replies';

    public function user()
    {
        return $this->morphTo();
    }

    public function getProcessedBodyAttribute()
    {
        $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        $body = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $this->body);

        if (strpos($body, 'youtube.com'))
            $body = preg_replace(
                "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
                "<div style='position: relative;padding-bottom: 56.25%;padding-top: 25px;height: 0;'><iframe src=\"//www.youtube.com/embed/$2\" allowfullscreen style='position: absolute;top: 0;left: 0;width: 100%;height: 100%;'></iframe></div>",
                $body
            );

        return $body;
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}
