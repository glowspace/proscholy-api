<?php

namespace App\Notifications;

use App\SongLyric;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SongLyricCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $user_name = Auth::user()->name;
        $song_id = $notifiable->id;
        $song_name = $notifiable->name;
        $song_url = $notifiable->getPublicUrlAttribute();

        $emoji = (new NotificationHelper())->getRandomEmoji();

        return (new SlackMessage)
            ->content($user_name . " přidal(a) do píseň :zpevnik:$song_id - <$song_url|$song_name> $emoji");
    }
}
