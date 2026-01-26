<?php

namespace App\Notifications;

use App\Models\Photo;
use App\Models\Recipe;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class RecipeCreated extends Notification
{
    use Queueable;

    public Recipe $recipe;

    /**
     * Create a new notification instance.
     */
    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
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

        $photo = $this->getPhoto($this->recipe);

        return OneSignalMessage::create()
            ->setSubject($this->recipe->name)
            ->setBody($this->recipe->description)
            ->setData('recipe_id', $this->recipe->id)
            ->setIcon('ic_stat_onesignal_default')
            ->setImageAttachments($photo->url) // Rich notification image
            ->webButton(
                OneSignalWebButton::create('view-recipe-details')
                    ->text('View Details')
                    ->icon('https://flobaze.atl1.cdn.digitaloceanspaces.com/public/chefpilot_icon.png')
                    ->url(route("recipe.publicPost", $this->recipe->ulid))
            );
    }


    public function toDatabase(object $notifiable): array
    {
        $photo = $this->getPhoto($this->recipe);

        return [
            "recipe_id" => $this->recipe->id,
            "message" => $this->recipe->name,
            "description" => $this->recipe->description,
            "image_url" => $photo->url,
            "route" => "/recipe/{$this->recipe->id}",
            "type" => "recipe",
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


    private function getPhoto(Recipe $recipe): Photo
    {
        $photo = Photo::find($recipe->photo_id);

        if ($photo) {
            return $photo;
        }

        return Photo::where("name","default")->first();

    }
}
