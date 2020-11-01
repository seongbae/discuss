<?php

namespace Seongbae\Discuss\Http\Controllers;

use Seongbae\Discuss\Models\Thread;
use Illuminate\Http\Request;
use Seongbae\Discuss\Models\Channel;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update($type, $id)
    {
        $user = Auth::user();

        if ($type == 'thread')
        {
            $thread = Thread::find($id);
            $thread->updateSubscription($user);
        }
        elseif ($type == 'channel')
        {
            $channel = Channel::find($id);
            $channel->updateSubscription($user);
        }
    }

}
