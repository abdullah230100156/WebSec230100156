<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $link;
    private $name;

    public function __construct($link, $name)
    {
        $this->link = $link;
        $this->name = $name;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verification', // ✅ Correct View Name
            with: [
                'link' => $this->link,
                'name' => $this->name,
            ],
        );
    }
}
