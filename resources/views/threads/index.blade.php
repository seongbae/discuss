@extends('layouts.app')
@section('content')
<section>
	<div class="container">
		<div class="row">
			<div class="col-lg-2">
                <a class="btn btn-primary  btn-sm w-100 mb-2" href="#" data-target="#newThread" data-toggle="modal"><i class="fas fa-plus"></i> Start a Discussion</a>
                <a class="btn btn-outline-primary btn-sm w-100 mb-2" href="/discuss" >View All Threads</a>
                @auth
                <a class="btn btn-outline-primary btn-sm w-100 mb-2" href="/discuss?user=me" >My Threads</a>
                    @if (request()->is('discuss/*'))
                        <hr>
{{--                    <a class="btn btn-outline-primary btn-sm w-100 mb-2" href="/discuss?user=me" >Subscribe to Channel</a>--}}
                        <channel-subscribe :channel="{{$channel}}" :user="{{Auth::user()}}" :subscribed="'{{ $subscribed }}'"></channel-subscribe>
                        @endif
                @endauth


            </div>
			<div class="col-lg-10">
                @if (count($threads)>0)
				<div class="card card-default">
					<div class="card-body">
						@foreach ($threads as $thread)
						@if ($thread->user != null)
						<article>
							<div style="float:left;" class="mr-4">
								<img src="{{getUserImage($thread->user, config('discuss.user_image_field'), config('discuss.user_image_path'), config('discuss.default_image'))}}" class="rounded-circle" width="50px">
							</div>
							<div style="float:left;">
								<a href="{{ $thread->path() }}" style="font-size:1.2em;color:#22292f;">
									{{ $thread->title }}
								</a>
                                <div style="color:grey;" class="mt-1">
                                    <a href="#" tabindex="0" role="button" data-placement="right" data-toggle="popover" data-container="body" type="button" data-html="true" id="login" data-id="{{$thread->user->id}}">{{ $thread->user->name}}</a>
                                    created {{ $thread->created_at->diffForHumans() }} in

                                    <a href="/discuss/{{$thread->channel->slug}}" class="{{ $thread->channel->css_classes }}" style="border-radius:40px;">{{$thread->channel->name}}</a>

                                    @include('discuss::threads._partials.userpopover',['user'=>$thread->user])
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
                        {{ $thread = null }}
					</div>
				</div>
                @endif
			</div>
		</div>
	</div>
</section>
<div class="modal fade" id="newThread" tabindex="-1" role="dialog" aria-labelledby="newThreadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">New Thread</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{route('discuss.store')}}">
                        @csrf
                        @include('discuss::threads._partials.form', ['thread', null])
                    </form>
                </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer src="/js/all.js"></script>

    <script>
    $(document).ready(function(){
        $('#newThread').on('shown.bs.modal', function() {
            $('#title').val("");
            $('#title').trigger('focus');
        });
    });
    </script>
@endpush
