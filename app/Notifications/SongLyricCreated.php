<?php

namespace App\Notifications;

use App\SongLyric;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use SnoerenDevelopment\DiscordWebhook\DiscordMessage;
use SnoerenDevelopment\DiscordWebhook\DiscordWebhookChannel;

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
        return [DiscordWebhookChannel::class];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toDiscord($notifiable)
    {
        $user_name = Auth::user()->name;
        $song_id = $notifiable->id;
        $song_name = $notifiable->name;
        $song_url = $notifiable->getPublicUrlAttribute();

        $emoji = (new NotificationHelper())->getRandomEmoji();

        return DiscordMessage::create()
            ->username('Redakční bot')
            ->content(sprintf("%s přidal(a) píseň #%s [%s](%s). %s", $user_name, $song_id, $song_name, $song_url, $emoji))
            ->tts(false);
    }
}
