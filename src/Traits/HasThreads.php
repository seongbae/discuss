<?php

namespace Seongbae\Discuss\Traits;

use Seongbae\Discuss\Models\Thread;

trait HasThreads
{
    public function threads()
    {
        return $this->morphMany(Thread::class, 'user');
    }
}
