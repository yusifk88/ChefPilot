<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class NewFollowerNotification extends Notification
{
    use Queueable;

    private User $follower;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $follower)
    {
        $this->follower = $follower;
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


    /**
     * Get the onesignal representation of the notification.
     */
    public function toOneSignal(object $notifiable): OneSignalMessage
    {

        return OneSignalMessage::create()
            ->setSubject("You have a new follower 🎉")
            ->setBody($this->follower->name . " just followed you")
            ->setData('recipe_id', $this->follower->id)
            ->setIcon('ic_stat_onesignal_default')
            ->webButton(
                OneSignalWebButton::create('view-follower-details')
                    ->text('View Details')
                    ->icon('https://flobaze.atl1.cdn.digitaloceanspaces.com/public/chefpilot_icon.png')
                    ->url(config('app.url'))
            );

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

    public function toDatabase(object $notifiable): array
    {
        return [
            "follower_id" =>$this->follower->id,
            "message" => "You have a new follower 🎉",
            "description" => $this->follower->name . " just followed you",
            "type" => "follow",
        ];

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
