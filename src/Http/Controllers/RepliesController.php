<?php

namespace Seongbae\Discuss\Http\Controllers;


use Illuminate\Http\Request;
use Seongbae\Discuss\Models\Thread;
use Illuminate\Support\Facades\Auth;
use Seongbae\Discuss\Models\Reply;
use App\Http\Controllers\Controller;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Persist a new reply.
     *
     * @param  Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), [
            'body'=>'required'
        ]);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => Auth::id(),
            'user_type' => $thread->user_type
        ], request('subscribe') == '1');

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
         $this->validate(request(), [
             'body'=>'required'
         ]);

        $reply->update(request(['body']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $reply->delete();

        return back();
    }
}
