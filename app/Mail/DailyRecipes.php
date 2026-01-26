<?php

namespace App\Mail;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailyRecipes extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $newRecipes;
    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $recipes,User $user)
    {
        $IDs = $recipes->select("id")->pluck("id")->toArray();


        $this->newRecipes = Recipe::with("photos")->whereIn("id", $IDs)->get();
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Today's Recipes"
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.dailyrecipes',
            with: ["recipes"=>$this->newRecipes,"user"=>$this->user]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
