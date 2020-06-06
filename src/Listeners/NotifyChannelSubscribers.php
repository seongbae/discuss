<?php

namespace Seongbae\Discuss\Listeners;

use Illuminate\Support\Facades\Log;
use Notification;
use Seongbae\Discuss\Events\NewThread;
use Seongbae\Discuss\Notifications\NewThreadNotification;
use Auth;

class NotifyChannelSubscribers
{
    public function handle(NewThread $event)
    {
        $subject = 'New discussion started in '.$event->getThread()->channel->name;

        Notification::send($event->getThread()->channel->subscribersExcept, new NewThreadNotification($event->getThread(), $subject));
    }
}
