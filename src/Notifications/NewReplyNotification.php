<?php

namespace Seongbae\Discuss\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Mail\TeamMemberAdded;
use Helper;

class NewReplyNotification extends Notification
{
    use Queueable;

    private $user;
    private $reply;
    private $msg;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($reply, $msg=null)
    {
        $this->reply = $reply;
        $this->msg = $msg;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('discuss.show', ['channel'=>$this->reply->thread->channel->slug, 'thread'=>$this->reply->thread]);

        return (new MailMessage)
            ->subject($this->msg)
            ->markdown('discuss::emails.newreply',['reply'=>$this->reply,'thread'=>$this->reply->thread, 'url'=>$url]);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

        ];
    }
}
