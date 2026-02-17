<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class PostInteractionNotification extends Notification
{
    use Queueable;

    private Post $post;
    private string $type;
    private User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post, string $type, User $user)
    {
        $this->post = $post;
        $this->type = $type;
        $this->user = $user;
    }


    /**
     * Get the onesignal representation of the notification.
     */
    public function toOneSignal(object $notifiable): OneSignalMessage
    {

        return OneSignalMessage::create()
            ->setSubject($this->customDetails()->title)
            ->setBody($this->customDetails()->description)
            ->setData('recipe_id', $this->post->ulid)
            ->setIcon('ic_stat_onesignal_default')
            ->webButton(
                OneSignalWebButton::create('view-follower-details')
                    ->text('View Details')
                    ->icon('https://flobaze.atl1.cdn.digitaloceanspaces.com/public/chefpilot_icon.png')
                    ->url(config('app.url'))
            );

    }

    /**
     * @return object
     * get customer properties for notifications
     */
    private function customDetails(): object
    {
        $type = match ($this->type) {
            "likes" => "like_interaction",
            "comments" => "comment_interaction",
            default => "interaction"
        };


        $title = match ($this->type) {
            "likes" => "Someone liked your recipe post",
            "comments" => "Someone commented on your recipe post",
            default => "Your post is gaining attention, check it out!"
        };

        $description = match ($this->type) {
            "likes" => "{$this->user->name} liked your recipe post",
            "comments" => "{$this->user->name} commented on your recipe post",
            default => "Your post is gaining attention, check it out!"
        };


        return (object)[
            "type" => $type,
            "title" => $title,
            "description" => $description,
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [OneSignalChannel::class, "database"];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            "post_id" => $this->post->id,
            "post_ulid" => $this->post->ulid,
            "message" => $this->customDetails()->title,
            "description" => $this->customDetails()->description,
            "type" => $this->customDetails()->type,
        ];

    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
