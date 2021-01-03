<?php

namespace Seongbae\Discuss\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Seongbae\Discuss\Models\Subscription;
use Seongbae\Discuss\Models\Thread;
use Illuminate\Http\Request;
use Seongbae\Discuss\Models\Channel;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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
    public function store(Request $request, User $user)
    {
        if ($request->type == 'thread')
            $subscribable = Thread::find($request->id);
        elseif ($request->type == 'channel')
            $subscribable = Channel::find($request->id);

        $subscribable->attachSubscriber($user);

        if ($request->ajax())
            return $request->json([], 200);
        else
            return redirect()->back();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->type == 'thread')
            $subscribable = Thread::find($request->id);
        elseif ($request->type == 'channel')
            $subscribable = Channel::find($request->id);

        $subscribable->detachSubscriber($user);

        if ($request->ajax())
            return $request->json([], 200);
        else
            return redirect()->back();

    }

}
