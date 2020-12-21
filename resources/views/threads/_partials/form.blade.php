<div class="row mb-2">
    <div class="col-sm-12">
        <input type="text" name="title" placeholder="Title" class="form-control" id="title" value="{{ $thread ? $thread->title : "" }}" focus/>
    </div>
</div>
<div class="row mb-2">
    <div class="col-sm-12">
        <select name="channel_id" id="channel_id" class="form-control" required>
            <option value="">Choose Category</option>

            @foreach (\Seongbae\Discuss\Models\Channel::all() as $channel)
                <option value="{{ $channel->id }}" {{ $thread && $thread->channel->id == $channel->id ? 'selected' : '' }}>
                    {{ $channel->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="row mb-2">
    <div class="col-sm-12">
        <textarea name="body" id="body" rows="8"  required="required" class="form-control" placeholder="Message...">{{ $thread ? $thread->body : "" }}</textarea>
    </div>
</div>
<div class="row mb-2">
    <div class="col-sm-12 text-right">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
    </div>
</div>
