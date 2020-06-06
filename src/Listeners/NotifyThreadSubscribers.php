<?php

namespace Seongbae\Discuss\Listeners;

use Illuminate\Support\Facades\Log;
use Notification;
use Seongbae\Discuss\Events\NewReply;
use Seongbae\Discuss\Notifications\NewReplyNotification;
use Auth;

class NotifyThreadSubscribers
{
    public function handle(NewReply $event)
    {
        $subject = $event->getReply()->user->name." replied \"".limitText($event->getReply()->body, 40)."\"";

        Notification::send($event->getReply()->thread->subscribersExcept, new NewReplyNotification($event->getReply(), $subject));
    }
}
