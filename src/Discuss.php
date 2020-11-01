<?php

namespace Seongbae\Discuss;

use Seongbae\Discuss\Models\Thread;

class Discuss
{
    public function getThreads($count=10, $orderBy='id', $orderDir='desc')
    {
        $threads = Thread::orderBy($orderBy,$orderDir)->take($count)->get();

        return $threads;
    }

}
