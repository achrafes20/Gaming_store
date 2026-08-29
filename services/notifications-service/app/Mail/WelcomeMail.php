<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $user) {}

    public function build()
    {
        return $this->subject('Welcome to NextLevelGaming!')
            ->view('emails.welcome', ['user' => $this->user]);
    }
}
