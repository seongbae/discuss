<form method="POST" action="/discuss">
    {{ csrf_field() }}

     <div class="form-group">
        <label for="channel_id">Category</label>
        <select name="channel_id" id="channel_id" class="form-control" required>
            <option value="">Choose One...</option>

            @foreach ($channels as $channel)
                <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                    {{ $channel->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" required>
    </div>

    <div class="form-group">
        <label for="body">Body:</label>
        <textarea name="body" id="body" class="form-control" rows="8" value="{{old('body')}}" required></textarea>
    </div>
{{--    <div class="form-group">--}}
{{--            <div class="custom-control custom-checkbox mr-sm-2">--}}
{{--                <input type="checkbox" class="custom-control-input" id="notify_users" name="notify_users" value="check" checked>--}}
{{--                <label class="custom-control-label" for="notify_users">Notify users</label>--}}
{{--            </div>--}}
{{--        </div>--}}
    <button type="submit" class="btn btn-primary">Publish</button>
    <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">Cancel</a>
</form>
