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

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}
