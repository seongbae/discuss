<?php

namespace Seongbae\Discuss\Http\Controllers;


use Illuminate\Http\Request;
use Seongbae\Discuss\Models\Thread;
use Seongbae\Discuss\Models\Channel;
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
    public function store(Request $request, Channel $channel, Thread $thread)
    {
        $this->validate(request(), [
            'body'=>'required'
        ]);

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => Auth::id(),
            'user_type' => $thread->user_type
        ], request('subscribe') == '1');

        if ($request->ajax())
            return $request->json([$reply], 200);
       else
            return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
         $this->validate(request(), [
             'body'=>'required'
         ]);

        if ($reply->user_id != $request->user()->id)
            return redirect()->back();

        $reply->update(request(['body']));

        if ($request->ajax())
            return $request->json([$reply], 200);
        else
            return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply, Request $request)
    {
        if ($reply->user_id != $request->user()->id)
            return redirect()->back();

        $reply->delete();

        if ($request->ajax())
            return $request->json([], 200);
        else
            return redirect()->back();
    }
}
