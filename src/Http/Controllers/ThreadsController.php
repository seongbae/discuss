<?php

namespace Seongbae\Discuss\Http\Controllers;

use Seongbae\Discuss\Models\Thread;
use Illuminate\Http\Request;
use Seongbae\Discuss\Models\Channel;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Log;

class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        if (config('discuss.view_mode') == 'public')
            $this->middleware('auth', ['only' => ['create', 'store', 'edit','update', 'delete']]);
        else
            $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug=null)
    {
        $subscribed = false;
        $channel = null;

        if ($slug)
        {
            $threads = Thread::whereHas('channel', function($q) use($slug) {
                $q->where('channels.slug', $slug);
            })->latest()->paginate(config('discuss.page_count'));

            $channel = Channel::where('slug', $slug)->first();

            $subscribed = Auth::user()->subscribedTo($channel);
        }
        else
        {
            if (request()->get('user'))
            {
                $user = Auth::user();
                $threads = Thread::where('user_id',$user->id)->latest()->paginate(config('discuss.page_count'));
            }
            else
                $threads = Thread::latest()->paginate(config('discuss.page_count'));
        }

        return view('discuss::threads.index', compact('threads', 'channel','subscribed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channels = Channel::all();

        return view('discuss::threads.create')
                ->with('channels', $channels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'=>'required',
            'body'=>'required',
            'channel_id'=>'required|exists:channels,id'
        ]);

        $user = Auth::user();

        $thread = $user->threads()->create([
            'user_id' => $user->id,
            'title' => request('title'),
            'slug' => $this->slugify(request('title')),
            'channel_id' => request('channel_id'),
            'body'  => request('body')
        ]);

        $thread->attachSubscriber($user);

        if ($request->ajax())
            return $request->json([$thread], 200);
        else
            return redirect()->route('discuss.index')->with('success','Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread)
    {
        $channels = Channel::all();

        $thread->view_count += 1;
        $thread->save();

        $user = Auth::user();

        return view('discuss::threads.show', compact(['thread', 'channels','user']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Channel $channel, Thread $thread, Request $request)
    {
        $this->validate(request(), [
            'title'=>'required',
            'body'=>'required',
            'channel_id'=>'required|exists:channels,id'
        ]);

        $thread->update(request(['title','body','channel_id']));

        if ($request->ajax())
            return $request->json([$thread], 200);
        else
            return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Channel $channel, Thread $thread)
    {
        $thread->delete();

        if ($request->ajax())
            return $request->json([], 200);
        else
            return redirect()->route('discuss.index')->with('success','Successfully deleted');
    }

    private function slugify($string){
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));

        $thread = Thread::where('slug', $slug)->first();

        if ($thread)
            $slug = $slug.'-'.$this->generate_string(4);

        return $slug;
    }


    private function generate_string($strength = 4) {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $input_length = strlen($permitted_chars);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }
}
