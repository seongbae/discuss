@extends('layouts.app')
@section('content')
<section>
	<div class="container">
		<div class="row">
			<div class="col-lg-2">
                <a class="btn btn-primary  btn-sm w-100 mb-2" href="/discuss/new" ><i class="fas fa-plus"></i> Start a Discussion</a>
                <a class="btn btn-outline-primary btn-sm w-100 mb-2" href="/discuss" >View All Threads</a>
                @if (auth())
                <a class="btn btn-outline-primary btn-sm w-100 mb-2" href="/discuss?user=me" >My Threads</a>
                    @if (request()->is('discuss/*'))
                        <hr>
{{--                    <a class="btn btn-outline-primary btn-sm w-100 mb-2" href="/discuss?user=me" >Subscribe to Channel</a>--}}
                        <channel-subscribe :channel="{{$channel}}" :user="{{Auth::user()}}" :subscribed="'{{ $subscribed }}'"></channel-subscribe>
                        @endif
                @endif


            </div>
			<div class="col-lg-10">
                @if (count($threads)>0)
				<div class="card card-default">
					<div class="card-body">
						@foreach ($threads as $thread)
						@if ($thread->user != null)
						<article>
							<div style="float:left;" class="mr-4">
								<img src="{{ config('discuss.user_image_field') == "" ? config('discuss.default_image') : config('discuss.user_image_path').$thread->user->{config('discuss.user_image_field')} }}" class="rounded-circle" width="50px">
							</div>
							<div style="float:left;">
								<a href="{{ $thread->path() }}" style="font-size:1.2em;color:#22292f;">
									{{ $thread->title }}
								</a>
								<div style="color:grey;">
									<a href="#" v-b-popover.hover.right="'{{Auth::user()->name}}'" title="{{Auth::user()->name}}">{{ $thread->user->name}}</a> created {{ $thread->created_at->diffForHumans() }} in
                                    <a href="/discuss/{{$thread->channel->slug}}" class="{{ $thread->channel->css_classes }}" style="border-radius:40px;">{{$thread->channel->name}}</a>

                                </div>
							</div>
							<div class="text-muted" style="text-align:right;">
								@if (count($thread->replies) > 0)
								<i class="fas fa-comment" title="Replies"></i> {{count($thread->replies)}}

								@endif
                                <i class="ml-2 far fa-eye" title="View count"></i> {{$thread->view_count}}
							</div>
							<br style="clear: left;" />
						</article>
						<hr>
						@endif
						@endforeach

                        {{ $threads->links() }}
					</div>
				</div>
                @endif
			</div>
		</div>
	</div>
</section>
@stop
